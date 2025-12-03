<?php

declare(strict_types=1);

namespace App\Core\FileManager\Entities;

use App\Contracts\Entity\BaseEntity;
use App\Contracts\Entity\TaggableEntity;
use App\Core\FileManager\Database\Factories\FileFactory;
use App\Core\FileManager\Enums\FileCollectionEnum;
use App\Core\FileManager\Enums\FileTypeEnum;
use App\Core\Organization\Entities\User;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string|null $entity_type
 * @property int|null $entity_id
 * @property string $original_name
 * @property string $file_name
 * @property string $file_path
 * @property string|null $file_url
 * @property string $disk
 * @property string $mime_type
 * @property string $extension
 * @property int $file_size
 * @property string|null $file_hash
 * @property FileTypeEnum $file_type
 * @property FileCollectionEnum|null $collection
 * @property int|null $width
 * @property int|null $height
 * @property string|null $aspect_ratio
 * @property int|null $duration
 * @property string|null $resolution
 * @property float|null $frame_rate
 * @property int|null $uploaded_by
 * @property int|null $folder_id
 * @property string|null $module_name
 * @property bool $is_public
 * @property int $download_count
 * @property int $view_count
 * @property \Carbon\Carbon|null $last_accessed_at
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * Relations:
 * @property Folder|null $folder
 * @property User|null $uploader
 */
class File extends BaseEntity implements TaggableEntity
{
    use HasFactory;
    use SoftDeletes;
    use Taggable;

    public const TABLE = 'core_file_files';

    protected $table = self::TABLE;

    protected $fillable = [
        'uuid',
        'entity_type',
        'entity_id',
        'original_name',
        'file_name',
        'file_path',
        'file_url',
        'disk',
        'title',
        'mime_type',
        'extension',
        'file_size',
        'file_hash',
        'file_type',
        'collection',
        'is_temporary',
        'expires_at',
        'width',
        'height',
        'aspect_ratio',
        'duration',
        'resolution',
        'frame_rate',
        'uploaded_by',
        'folder_id',
        'module_name',
        'is_public',
        'download_count',
        'view_count',
        'last_accessed_at',
        'is_active',
    ];

    protected $casts = [
        'file_type'        => FileTypeEnum::class,
        'collection'       => FileCollectionEnum::class,
        'width'            => 'integer',
        'height'           => 'integer',
        'duration'         => 'integer',
        'frame_rate'       => 'float',
        'uploaded_by'      => 'integer',
        'folder_id'        => 'integer',
        'is_public'        => 'boolean',
        'is_temporary'     => 'boolean',
        'download_count'   => 'integer',
        'view_count'       => 'integer',
        'last_accessed_at' => 'datetime',
        'expires_at'       => 'datetime',
        'is_active'        => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function entity(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'entity_type', 'entity_id');
    }

    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'entity');
    }

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }
}
