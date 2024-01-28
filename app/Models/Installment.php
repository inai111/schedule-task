<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $table = 'installments';
    protected $fillable = ['total_price','installments','pay_before_date'];

    public static function boot()
    {
        parent::boot();
        static::creating(function($table){
            $installment = $table->order->installments()->count();
            $date = Carbon::now()->addMonth()->lastOfMonth()->toDateString();
            
            $table->installments = $installment+1;
            $table->pay_before_date = $date;
        });
    }

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
