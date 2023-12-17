<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['plan_date'];

    public function transactions()
    {
        return $this->morphMany(Transaction::class,'transactionable');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
