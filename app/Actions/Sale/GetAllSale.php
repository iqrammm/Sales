<?php

namespace App\Actions\Sale;

use App\Http\Resources\SaleResource;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllSale
{
    use AsAction;

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function handle(Request $request): AnonymousResourceCollection
    {
        $sales = Sale::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where('ref_num', 'LIKE', '%' .  $request->search .  '%');
            })
            ->paginate(20);

        return SaleResource::collection($sales);
    }
}
