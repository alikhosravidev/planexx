<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $field_id
 * @property int $validation_rule_id
 * @property string|null $value
 * @property string|null $error_message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read FormField $field
 * @property-read ValidationRule $validationRule
 */
class FieldValidation extends Model
{
    protected $table = 'core_field_validations';

    protected $fillable = [
        'field_id',
        'validation_rule_id',
        'value',
        'error_message',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }

    public function validationRule(): BelongsTo
    {
        return $this->belongsTo(ValidationRule::class, 'validation_rule_id');
    }
}
