<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEditHistory extends Model
{
    use HasFactory;

    public function historyable()
    {
        return $this->morphTo();
    }
}
