<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the product that owns the OrderDetails
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function deatilsable()
    {
        return $this->morphTo();
    }

    public function histories()
    {
        return $this->morphMany(OrderEditHistory::class, 'historyable');
    }
}
