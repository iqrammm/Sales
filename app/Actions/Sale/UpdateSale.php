<?php

namespace App\Actions\Sale;

use App\Aggregates\SaleRepository;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Traits\HasProcessSaleData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSale
{
    use AsAction;
    use HasProcessSaleData;

    private SaleRepository $repository;

    public function authorize():bool
    {
        return auth()->check();
    }

    public function __construct(SaleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $data, Sale $sale): Sale
    {
        $saleRepository = $this->repository->retrieve($sale->getAggregateRootId());

        $saleRepository->updateSale(collect($data));

        $this->repository->persist($saleRepository);

        return $sale->refresh();
    }

    public function asController(Request $request, Sale $sale): JsonResource
    {
        $data = $this->processData($request);

        return SaleResource::make($this->handle($data, $sale));
    }
}
