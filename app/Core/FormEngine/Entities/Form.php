<?php

declare(strict_types=1);

namespace App\Core\FormEngine\Entities;

use App\Core\FormEngine\Enums\AuthTypeEnum;
use App\Core\FormEngine\Enums\DisplayModeEnum;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property DisplayModeEnum $display_mode
 * @property AuthTypeEnum $auth_type
 * @property string|null $success_message
 * @property string|null $redirect_url
 * @property int|null $max_submissions
 * @property bool $is_active
 * @property int|null $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read User|null $creator
 * @property-read Collection<int, FormField> $fields
 * @property-read Collection<int, Submission> $submissions
 */
class Form extends Model
{
    use SoftDeletes;

    protected $table = 'core_forms';

    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'description',
        'display_mode',
        'auth_type',
        'success_message',
        'redirect_url',
        'max_submissions',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'display_mode'    => DisplayModeEnum::class,
        'auth_type'       => AuthTypeEnum::class,
        'is_active'       => 'boolean',
        'max_submissions' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class, 'form_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'form_id');
    }
}
