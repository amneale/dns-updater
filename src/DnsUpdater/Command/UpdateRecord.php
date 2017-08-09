<?php

namespace DnsUpdater\Command;

use DnsUpdater\Command\Contract\UpdateRecordRequest;
use DnsUpdater\Command\Contract\UpdateRecordResponse;
use DnsUpdater\Command\Repository\UpdateRecordRepository;
use DnsUpdater\Command\Service\IpResolver;
use DnsUpdater\IpAddress;
use DnsUpdater\Record;
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
            $record = $request->getRecord();

            $ipAddress = $this->ipResolver->getIpAddress();
            if (!$this->shouldUpdateRecord($record, $ipAddress)) {
                return;
            }

            $record->setData($ipAddress);
            $record = $this->recordRepository->persist($record);

            $this->cache->set($this->getCacheKey($record), (string) $ipAddress);
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
     * @param Record $record
     * @param IpAddress $ipAddress
     *
     * @return bool
     */
    private function shouldUpdateRecord(Record $record, IpAddress $ipAddress): bool
    {
        $cacheKey = $this->getCacheKey($record);

        if ($this->cache->has($cacheKey) && $this->cache->get($cacheKey) === (string) $ipAddress) {
            $this->logger->info(
                'IP unchanged',
                [
                    'domain' => $record->getDomain(),
                    'host' => $record->getHost(),
                    'type' => $record->getType(),
                    'data' => (string) $ipAddress,
                ]
            );

            return false;
        }

        return true;
    }

    /**
     * @param Record $record
     *
     * @return string
     */
    private function getCacheKey(Record $record): string
    {
        $host = str_replace('@', '_AT_', $record->getHost());

        return "ip_{$host}_{$record->getDomain()}";
    }
}
