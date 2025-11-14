<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Entities;

use App\Core\FormEngine\Enums\FieldTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $form_id
 * @property string $field_key
 * @property FieldTypeEnum $field_type
 * @property string $label
 * @property string|null $placeholder
 * @property string|null $default_value
 * @property int $order
 * @property bool $is_required
 * @property array|null $payload
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read \App\Core\FormEngine\Entities\Form $form
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Core\FormEngine\Entities\FieldOption> $options
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Core\FormEngine\Entities\FieldValidation> $validations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Core\FormEngine\Entities\SubmissionFieldValue> $submissionValues
 */
class FormField extends Model
{
    use SoftDeletes;

    protected $table = 'core_form_fields';

    protected $fillable = [
        'form_id',
        'field_key',
        'field_type',
        'label',
        'placeholder',
        'default_value',
        'order',
        'is_required',
        'payload',
        'is_active',
    ];

    protected $casts = [
        'field_type'  => FieldTypeEnum::class,
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
        'payload'     => 'array',
        'order'       => 'integer',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(FieldOption::class, 'field_id');
    }

    public function validations(): HasMany
    {
        return $this->hasMany(FieldValidation::class, 'field_id');
    }

    public function submissionValues(): HasMany
    {
        return $this->hasMany(SubmissionFieldValue::class, 'field_id');
    }
}
