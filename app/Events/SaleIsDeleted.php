<?php

namespace App\Events;

use App\Aggregates\SaleId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

class SaleIsDeleted implements SerializablePayload
{
    private string $uuid;

    public function __construct(SaleId $uuid)
    {
        $this->uuid = $uuid->toString();
    }

    public function toPayload(): array
    {
        return [
            'uuid' => $this->uuid,
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(SaleId::fromString($payload['uuid']));
    }
}
