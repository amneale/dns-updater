<?php

namespace DnsUpdater\Command;

use DnsUpdater\Command\Contract\UpdateRecordRequest;
use DnsUpdater\Command\Contract\UpdateRecordResponse;
use DnsUpdater\Command\Repository\UpdateRecordRepository;
use DnsUpdater\Command\Service\IpResolver;
use DnsUpdater\Ip;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class UpdateRecord
{
    /**
     * @var IpResolver
     */
    private $ipResolver;

    /**
     * @var UpdateRecordRepository
     */
    private $recordRepository;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param IpResolver $ipResolver
     * @param UpdateRecordRepository $recordRepository
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        IpResolver $ipResolver,
        UpdateRecordRepository $recordRepository,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->ipResolver = $ipResolver;
        $this->recordRepository = $recordRepository;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param UpdateRecordRequest $request
     * @param UpdateRecordResponse $response
     */
    public function handle(UpdateRecordRequest $request, UpdateRecordResponse $response)
    {
        try {
            $ip = $this->ipResolver->getIp();
            if (!$this->shouldUpdateRecord($ip)) {
                return;
            }

            $record = $request->getRecord();
            $record->setData($this->ipResolver->getIp());
            $record = $this->recordRepository->persist($record);

            $this->cache->set('ip', (string) $ip);
            $this->logger->info(
                'Updated record',
                [
                    'domain' => $record->getDomain(),
                    'host' => $record->getHost(),
                    'type' => $record->getType(),
                    'data' => $record->getData(),
                ]
            );

            $response->setRecord($record);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param Ip $ip
     *
     * @return bool
     */
    private function shouldUpdateRecord(Ip $ip): bool
    {
        if ($this->cache->has('ip') && $this->cache->get('ip') === (string) $ip) {
            $this->logger->info('IP unchanged', ['IP' => (string) $ip]);

            return false;
        }

        $this->logger->info('Detected a new IP', ['IP' => (string) $ip]);

        return true;
    }
}
