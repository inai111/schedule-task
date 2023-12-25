<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function vendors()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
