<?php

declare(strict_types=1);

namespace App\Core\FormWizard\Entities;

use App\Core\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $form_id
 * @property int|null $user_id
 * @property string|null $user_name
 * @property string|null $user_mobile
 * @property bool $is_verified
 * @property string|null $ip
 * @property string|null $user_agent
 * @property array|null $utm_params
 * @property \Carbon\Carbon $submitted_at
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \App\Core\FormWizard\Entities\Form $form
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Core\FormWizard\Entities\SubmissionFieldValue> $values
 */
class Submission extends Model
{
    protected $table = 'core_submissions';

    protected $fillable = [
        'form_id',
        'user_id',
        'user_name',
        'user_mobile',
        'is_verified',
        'ip',
        'user_agent',
        'utm_params',
        'submitted_at',
        'metadata',
    ];

    protected $casts = [
        'is_verified'  => 'boolean',
        'utm_params'   => 'array',
        'metadata'     => 'array',
        'submitted_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(SubmissionFieldValue::class, 'submission_id');
    }
}
