<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $field_id
 * @property string $label
 * @property string $value
 * @property int $sort_order
 *
 * @property-read \App\Core\FormWizard\Entities\FormField $field
 */
class FieldOption extends Model
{
    protected $table = 'core_field_options';

    protected $fillable = [
        'field_id',
        'label',
        'value',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
