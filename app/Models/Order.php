<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    //order status
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const PROCESSING = 'processing';
    public const SHIPPED = 'shipped';
    public const DELIVERED = 'delivered';

    protected $fillable = [
        'customer_id',
        'invoice_no',
        'total_amount',
        'status',
        'instruction',
    ];

    /**
     * Get the user that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function details()
    {
        return $this->morphMany(OrderDetails::class, 'deatilsable');
    }

    public function histories()
    {
        return $this->morphMany(OrderEditHistory::class, 'historyable');
    }
}
