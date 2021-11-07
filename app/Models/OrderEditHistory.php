<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEditHistory extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'order_data' => 'array'
    ];

    public function historyable()
    {
        return $this->morphTo();
    }
}
