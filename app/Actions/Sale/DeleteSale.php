<?php

namespace App\Actions\Sale;

use App\Aggregates\SaleRepository;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteSale
{
    use AsAction;

    private SaleRepository $repository;

    public function __construct(SaleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function handle(Request $request, Sale $sale): JsonResponse
    {
        $refNo = $sale->ref_num;

        $saleAggregate = $this->repository->retrieve($sale->getAggregateRootId());

        $saleAggregate->deleteSale();

        $this->repository->persist($saleAggregate);

        return response()->json([
            "Sale ${refNo} deleted"
        ]);
    }
}
