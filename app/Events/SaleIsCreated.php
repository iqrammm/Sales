<?php

namespace App\Events;

use App\Aggregates\SaleId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Illuminate\Support\Collection;

class SaleIsCreated implements SerializablePayload
{
    private string $uuid;
    private array $saleData;
    private array $saleItemData;

    public function __construct(SaleId $uuid, Collection $data)
    {
        $this->uuid = $uuid->toString();
        $this->saleData = $data['saleData'];
        $this->saleItemData = $data['saleItemData'];
    }

    public function toPayload(): array
    {
        return [
            'uuid' => $this->uuid,
            'saleData' => $this->saleData,
            'saleItemData' => $this->saleItemData
        ];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self(SaleId::fromString($payload['uuid']), collect($payload));
    }
}
