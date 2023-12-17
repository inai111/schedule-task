<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['nominal','note'];

    public static function boot()
    {
        parent::boot();
        static::creating(function($table){
            $table->exp_date = Carbon::now()->addHour(24);
        });
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function transaction_details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
