<?php

namespace DnsUpdater;

use Assert\Assert;

class Record
{
    const TYPE_ADDRESS = 'A';

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $data;

    /**
     * @param string $domain
     * @param string $host
     * @param string $type
     * @param string $data
     */
    public function __construct(string $domain, string $host, string $type, string $data = '')
    {
        Assert::that($type)->choice([
            self::TYPE_ADDRESS,
        ]);

        $this->type = $type;
        $this->domain = $domain;
        $this->host = $host;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data)
    {
        $this->data = $data;
    }

    /**
     * @param Record $record
     *
     * @return bool
     */
    public function isSame(Record $record)
    {
        return $this->domain === $record->getDomain()
            && $this->host === $record->getHost()
            && $this->type === $record->getType();
    }
}
