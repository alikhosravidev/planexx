<?php

declare(strict_types=1);

namespace Modules\Product\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Database\Factories\ProductFactory;
use Modules\Product\Enums\ProductStatusEnum;

/**
 * @property int                         $id
 * @property string                      $title
 * @property string                      $slug
 * @property int                         $price
 * @property int|null                    $sale_price
 * @property ProductStatusEnum           $status
 * @property bool                        $is_featured
 * @property int|null                    $created_by
 * @property int|null                    $updated_by
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Relations:
 * @property User|null $creator
 * @property User|null $updater
 * @property \Illuminate\Database\Eloquent\Collection<int, Category> $categories
 */
class Product extends BaseEntity
{
    use HasFactory;
    use SoftDeletes;
    use HasCreator;

    public const TABLE = 'product_products';

    protected $table = self::TABLE;

    protected $fillable = [
        'title',
        'slug',
        'price',
        'sale_price',
        'status',
        'is_featured',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price'       => 'integer',
        'sale_price'  => 'integer',
        'status'      => ProductStatusEnum::class,
        'is_featured' => 'boolean',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'product_category_product',
            'product_id',
            'category_id',
        );
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
