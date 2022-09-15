<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class License extends Model
{
    use HasFactory;
    protected $fillable = ['license_name', 'product_id'];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
