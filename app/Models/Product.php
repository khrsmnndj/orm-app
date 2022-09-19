<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\License;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Products",
 *     type="object",
 *
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          example="1"
 *     ),
 *
 *     @OA\Property(
 *          property="product_name",
 *          type="string",
 *          example="Smart Window"
 *     ),
 *
 *     @OA\Property(
 *          property="created_at",
 *          type="string",
 *          format="date-time",
 *          example="2022-05-13T00:00:00Z"
 *     ),
 *
 *     @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          format="date-time",
 *          example="2022-05-13T00:00:00Z"
 *     )
 * )
 */

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
