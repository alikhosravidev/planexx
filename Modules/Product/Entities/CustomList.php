<?php

declare(strict_types=1);

namespace Modules\Product\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Database\Factories\CustomListFactory;

/**
 * @property int                         $id
 * @property string                      $title
 * @property string                      $slug
 * @property string|null                 $description
 * @property string                      $icon_class
 * @property string                      $color
 * @property bool                        $is_active
 * @property int|null                    $created_by
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 *
 * Relations:
 * @property User|null $creator
 * @property \Illuminate\Database\Eloquent\Collection<int, CustomListAttribute> $attributes
 * @property \Illuminate\Database\Eloquent\Collection<int, CustomListItem> $items
 */
class CustomList extends BaseEntity
{
    use HasFactory;
    use HasCreator;

    public const TABLE = 'product_custom_lists';

    protected $table = self::TABLE;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon_class',
        'color',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(CustomListAttribute::class, 'list_id')
            ->orderBy('sort_order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CustomListItem::class, 'list_id');
    }

    protected static function newFactory(): CustomListFactory
    {
        return CustomListFactory::new();
    }
}
