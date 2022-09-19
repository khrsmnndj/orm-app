<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Licenses",
 *     type="object",
 *
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          example="1"
 *     ),
 *
 *     @OA\Property(
 *          property="license_name",
 *          type="string",
 *          example="ISO 20001"
 *     ),
 *
 *     @OA\Property(
 *          property="product_id",
 *          type="integer",
 *          example="1"
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

class License extends Model
{
    use HasFactory;
    protected $fillable = ['license_name', 'product_id'];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
