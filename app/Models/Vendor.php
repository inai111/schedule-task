<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name','address','category_id','phone_number',
        'bank_name','bank_account_name',
        'bank_account_number'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
