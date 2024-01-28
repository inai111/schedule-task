<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'total','note','status','vendor_id','order_id','schedule_id',
        'order_detail_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function transaction()
    {
        return $this->hasOneThrough(Transaction::class,TransactionDetail::class,'order_detail_id','id','id','transaction_id')
        ->where('transactions.status','success');
    }

    public function transactionDetail()
    {
        return $this->hasOne(TransactionDetail::class,'order_detail_id');
    }
}
