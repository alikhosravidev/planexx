<?php

declare(strict_types=1);

namespace Modules\Product\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\Organization\Entities\User;
use App\Core\Organization\Traits\HasCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Database\Factories\CustomListItemFactory;

/**
 * @property int                         $id
 * @property int                         $list_id
 * @property string|null                 $reference_code
 * @property int|null                    $created_by
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 *
 * Relations:
 * @property CustomList $list
 * @property User|null $creator
 * @property \Illuminate\Database\Eloquent\Collection<int, CustomListValue> $values
 */
class CustomListItem extends BaseEntity
{
    use HasFactory;
    use HasCreator;

    public const TABLE = 'product_custom_list_items';

    protected $table = self::TABLE;

    protected $fillable = [
        'list_id',
        'reference_code',
        'created_by',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(CustomList::class, 'list_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomListValue::class, 'item_id');
    }

    protected static function newFactory(): CustomListItemFactory
    {
        return CustomListItemFactory::new();
    }
}
