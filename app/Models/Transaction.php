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

    protected $fillable = ['nominal','note'];

    public static function boot()
    {
        parent::boot();
        static::creating(function($table){
            $table->exp_date = Carbon::now()->addDay()->format('Y-m-d 00:00:00');
            $table->slug = Uuid::uuid1();
        });
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
