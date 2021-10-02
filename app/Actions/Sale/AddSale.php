<?php

namespace App\Actions\Sale;

use App\Aggregates\SaleId;
use App\Aggregates\SaleRepository;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Traits\HasProcessSaleData;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class AddSale
{
    use AsAction;
    use HasProcessSaleData;

    private SaleRepository $repository;

    public function __construct(SaleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $data)
    {
        $uuid = SaleId::create();

        $sale = $this->repository->retrieve($uuid);

        $sale->createSale(collect($data));

        $this->repository->persist($sale);

        return Sale::query()->firstWhere('uuid', $uuid->toString());
    }

    public function asController(Request $request): SaleResource
    {
        $data = $this->processData($request);

        return SaleResource::make($this->handle($data));
    }
}
