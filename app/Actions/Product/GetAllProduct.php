<?php

namespace App\Actions\Product;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllProduct
{
    use AsAction;

    public function handle(Request $request):AnonymousResourceCollection
    {
        $sales = Product::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where('name', 'LIKE', '%' .  $request->search .  '%');
            })
            ->paginate(20);

        return ProductResource::collection($sales);
    }
}
