<?php

namespace App\Models;

use App\Aggregates\SaleId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Supplycart\Money\Casts\MoneyValue;
use EventSauce\EventSourcing\AggregateRootId;

class Sale extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'uuid',
        'user_id',
        'ref_num',
        'total_amount',
        'date',
    ];

    protected $casts = [
        'total_amount' => MoneyValue::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return SaleId::fromString($this->uuid);
    }
}
