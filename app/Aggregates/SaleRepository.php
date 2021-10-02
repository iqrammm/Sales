<?php

declare(strict_types=1);

namespace App\Aggregates;

use App\Consumers\SaleConsumer;
use EventSauce\LaravelEventSauce\AggregateRootRepository;

/** @method \App\Aggregates\Sale retrieve(\App\Aggregates\SaleId $aggregateRootId) */
final class SaleRepository extends AggregateRootRepository
{
    protected string $aggregateRoot = Sale::class;

    protected string $table = 'sale_domain_messages';

    protected array $consumers = [
        SaleConsumer::class
    ];
}
