<?php

declare(strict_types=1);

namespace App\Entities;

use App\Contracts\Entity\BaseEntity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int                 $id
 * @property string              $name
 * @property string|null         $slug
 * @property string|null         $description
 * @property string|null         $color
 * @property string|null         $icon
 * @property int                 $usage_count
 * @property \Carbon\Carbon      $created_at
 * @property \Carbon\Carbon      $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Tag extends BaseEntity
{
    use HasFactory;
    use SoftDeletes;

    protected bool $shouldLogActivity = true;

    public const TABLE = 'app_tags';

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'usage_count',
    ];

    protected $casts = [
        'usage_count' => 'integer',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: function (null|string $value) {
                $name                     = trim($value);
                $this->attributes['name'] = $name;

                if (! isset($this->attributes['slug']) || empty($this->attributes['slug'])) {
                    $this->attributes['slug'] = $name ? Str::slug(Str::lower($name)) : null;
                }
            },
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: function (null|string $value) {
                $base                     = $value !== null && $value !== '' ? $value : ($this->attributes['name'] ?? null);
                $this->attributes['slug'] = $base ? Str::slug(Str::lower((string) $base)) : null;
            },
        );
    }

    public function entitiesOfType(string $modelClass): MorphToMany
    {
        return $this->morphedByMany($modelClass, 'entity', 'app_entity_has_tags')
            ->withTimestamps();
    }
}
