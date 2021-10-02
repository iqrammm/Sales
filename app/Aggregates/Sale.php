<?php

declare(strict_types=1);

namespace App\Aggregates;

use App\Events\SaleIsCreated;
use App\Events\SaleIsDeleted;
use App\Events\SaleIsUpdated;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Illuminate\Support\Collection;

final class Sale implements AggregateRoot
{
    use AggregateRootBehaviour;

    public ?string $uuid;

    public ?array $saleData;

    public ?array $saleItemData;

    public function createSale(Collection $data)
    {
        $this->recordThat(new SaleIsCreated($this->aggregateRootId(), $data));
    }

    public function applySaleIsCreated(SaleIsCreated $saleIsCreated): void
    {
        $payload = $saleIsCreated->toPayload();

        $this->uuid = $payload['uuid'];
        $this->saleData = $payload['saleData'];
        $this->saleItemData = $payload['saleItemData'];
    }

    public function updateSale(Collection $data)
    {
        $this->recordThat(new SaleIsUpdated($this->aggregateRootId(), $data));
    }

    public function applySaleIsUpdated(SaleIsUpdated $saleIsUpdated)
    {
        $payload = $saleIsUpdated->toPayload();

        $this->saleData = $payload['saleData'];
        $this->saleItemData = $payload['saleItemData'];
    }

    public function deleteSale()
    {
        $this->recordThat(new SaleIsDeleted($this->aggregateRootId()));
    }

    public function applySaleIsDeleted()
    {

    }

}
