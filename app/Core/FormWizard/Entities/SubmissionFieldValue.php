<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $submission_id
 * @property int $field_id
 * @property string|null $value
 * @property string|null $file_url
 * @property array|null $file_metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \App\Core\FormWizard\Entities\Submission $submission
 * @property-read \App\Core\FormWizard\Entities\FormField $field
 */
class SubmissionFieldValue extends Model
{
    protected $table = 'core_submission_values';

    protected $fillable = [
        'submission_id',
        'field_id',
        'value',
        'file_url',
        'file_metadata',
    ];

    protected $casts = [
        'file_metadata' => 'array',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, 'submission_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
