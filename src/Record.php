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
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value
    ;

    /**
     * @param string $domain
     * @param string $name
     * @param string $type
     * @param string $value
     */
    public function __construct(string $domain, string $name, string $type, string $value)
    {
        $this->type = $type;
        $this->domain = $domain;
        $this->name = $name;
        $this->value = $value;
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
    public function getName(): string
    {
        return $this->name;
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
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param Record $record
     *
     * @return bool
     */
    public function isSame(Record $record)
    {
        return $this->domain === $record->getDomain()
            && $this->name === $record->getName()
            && $this->type === $record->getType();
    }
}
