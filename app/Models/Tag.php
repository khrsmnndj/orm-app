<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Tags",
 *     type="object",
 *
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          example="1"
 *     ),
 *
 *     @OA\Property(
 *          property="tag_name",
 *          type="string",
 *          example="Microsoft"
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

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['tag_name'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags');
    }
}
