<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::updated(function($table){
            if($table->status == 'rejected'){
                $table->order->total_price -= $table->orderDetails->sum(fn($detail) => $detail['total_price']);
            }
        });
    }

    protected $fillable = [
        'title','date','location','note'
    ];

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class,'staff_wo_id');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
