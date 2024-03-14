<?php

declare(strict_types=1);

namespace DnsUpdater\Value;

class Record
{
    public const TYPE_ADDRESS = 'A';

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
    private $value;

    public function __construct(string $domain, string $name, string $type, string $value)
    {
        $this->type = $type;
        $this->domain = $domain;
        $this->name = $name;
        $this->value = $value;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
