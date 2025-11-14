<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Core\FormEngine\Entities\FieldValidation> $fieldValidations
 */
class ValidationRule extends Model
{
    protected $table = 'core_validation_rules';

    protected $fillable = [
        'name',
        'description',
    ];

    public function fieldValidations(): HasMany
    {
        return $this->hasMany(FieldValidation::class, 'validation_rule_id');
    }
}
