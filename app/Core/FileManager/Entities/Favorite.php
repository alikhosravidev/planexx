<?php

declare(strict_types=1);

namespace App\Core\FileManager\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Core\Organization\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends BaseEntity
{
    use HasFactory;

    public const TABLE = 'core_file_favorites';

    protected $table = self::TABLE;

    protected $fillable = [
        'user_id',
        'entity_id',
        'entity_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'entity_type', 'entity_id');
    }
}
