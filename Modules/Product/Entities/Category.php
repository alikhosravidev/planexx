<?php

declare(strict_types=1);

namespace Modules\Product\Entities;

use App\Contracts\Entity\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Database\Factories\CategoryFactory;

/**
 * @property int                         $id
 * @property string                      $name
 * @property string                      $slug
 * @property string|null                 $description
 * @property int|null                    $parent_id
 * @property string|null                 $icon_class
 * @property int                         $sort_order
 * @property bool                        $is_active
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Relations:
 * @property Category|null $parent
 * @property \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property \Illuminate\Database\Eloquent\Collection<int, Product> $products
 */
class Category extends BaseEntity
{
    use HasFactory;
    use SoftDeletes;

    public const TABLE = 'product_categories';

    public const PIVOT_TABLE = 'product_category_product';

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'icon_class',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_category_product',
            'category_id',
            'product_id',
        );
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
