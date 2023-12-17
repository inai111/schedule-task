<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Installment extends Pivot
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class,Order::class);
    }
}
