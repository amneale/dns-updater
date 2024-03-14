<?php

declare(strict_types=1);

namespace DnsUpdater\Value;

class Record
{
    public const TYPE_ADDRESS = 'A';

    public function __construct(
        private readonly string $domain,
        private readonly string $name,
        private readonly string $type,
        private readonly string $value
    ) {}

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
