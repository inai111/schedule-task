<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Installment extends Pivot
{
    protected $table = 'installments';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class,'transactionable');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class,Order::class);
    }
}
