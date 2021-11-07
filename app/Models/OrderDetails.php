<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    public function deatilsable()
    {
        return $this->morphTo();
    }

    public function histories()
    {
        return $this->morphMany(OrderEditHistory::class, 'historyable');
    }
}
