<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_price','note','photo'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
