<?php

declare(strict_types=1);

namespace Modules\Product\Entities;

use App\Contracts\Entity\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Database\Factories\CustomListValueFactory;

/**
 * @property int              $id
 * @property int              $item_id
 * @property int              $attribute_id
 * @property string|null      $value_text
 * @property float|null       $value_number
 * @property \Carbon\Carbon|null $value_date
 * @property bool|null        $value_boolean
 *
 * Relations:
 * @property CustomListItem      $item
 * @property CustomListAttribute $attribute
 */
class CustomListValue extends BaseEntity
{
    use HasFactory;

    public const TABLE = 'product_custom_list_values';

    protected $table = self::TABLE;

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'attribute_id',
        'value_text',
        'value_number',
        'value_date',
        'value_boolean',
    ];

    protected $casts = [
        'value_number'  => 'decimal:4',
        'value_date'    => 'datetime',
        'value_boolean' => 'boolean',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(CustomListItem::class, 'item_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(CustomListAttribute::class, 'attribute_id');
    }

    protected static function newFactory(): CustomListValueFactory
    {
        return CustomListValueFactory::new();
    }
}
