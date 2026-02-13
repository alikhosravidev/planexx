<?php

declare(strict_types=1);

namespace Modules\Product\Entities;

use App\Contracts\Entity\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Database\Factories\CustomListAttributeFactory;
use Modules\Product\Enums\AttributeDataTypeEnum;

/**
 * @property int                    $id
 * @property int                    $list_id
 * @property string                 $label
 * @property string                 $key_name
 * @property AttributeDataTypeEnum  $data_type
 * @property bool                   $is_required
 * @property int                    $sort_order
 *
 * Relations:
 * @property CustomList $list
 * @property \Illuminate\Database\Eloquent\Collection<int, CustomListValue> $values
 */
class CustomListAttribute extends BaseEntity
{
    use HasFactory;

    public const TABLE = 'product_custom_list_attributes';

    protected $table = self::TABLE;

    public $timestamps = false;

    protected $fillable = [
        'list_id',
        'label',
        'key_name',
        'data_type',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'data_type'   => AttributeDataTypeEnum::class,
        'is_required' => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(CustomList::class, 'list_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomListValue::class, 'attribute_id');
    }

    protected static function newFactory(): CustomListAttributeFactory
    {
        return CustomListAttributeFactory::new();
    }
}
