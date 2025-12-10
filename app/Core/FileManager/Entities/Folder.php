<?php

declare(strict_types=1);

namespace App\Core\FileManager\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Contracts\Entity\FavoritableEntity;
use App\Contracts\Entity\TaggableEntity;
use App\Core\FileManager\Database\Factories\FolderFactory;
use App\Entities\Favorite;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $module_name
 * @property string $name
 * @property bool $is_public
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * Relations:
 * @property Folder|null $parent
 * @property \Illuminate\Database\Eloquent\Collection<int, Folder> $children
 * @property \Illuminate\Database\Eloquent\Collection<int, File> $files
 */
class Folder extends BaseEntity implements TaggableEntity, FavoritableEntity
{
    use HasFactory;
    use SoftDeletes;
    use Taggable;

    public const TABLE = 'core_file_folders';

    protected bool $shouldLogActivity = true;

    protected $table = self::TABLE;

    protected $fillable = [
        'parent_id',
        'module_name',
        'name',
        'is_public',
        'color',
        'icon',
        'description',
        'order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'order'     => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'folder_id');
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'entity');
    }

    protected static function newFactory(): FolderFactory
    {
        return FolderFactory::new();
    }
}
