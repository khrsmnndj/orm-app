<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\License;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['product_name'];

    public function licenses()
    {
        return $this->hasOne(License::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function delete()
    {
        License::where("product_id", $this->id)->delete();
        return parent::delete();
    }
}
