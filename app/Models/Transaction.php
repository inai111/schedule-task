<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['total'];

    public static function boot()
    {
        parent::boot();
        static::creating(function($table){
            if(!$table->exp_date){
                $table->exp_date = Carbon::now()->addDay()->format('Y-m-d 00:00:00');
            }
            $table->slug = Uuid::uuid1();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function user()
    {
        return $this->hasOneThrough(User::class,Order::class,'id','id','order_id','user_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
