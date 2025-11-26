<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\Organization\Database\Factories\JobPositionFactory;
use App\Core\Organization\Enums\TierEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                         $id
 * @property string                      $title
 * @property string|null                 $code
 * @property TierEnum|null               $tier
 * @property string|null                 $image_url
 * @property string|null                 $description
 * @property bool                        $is_active
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Accessors:
 * @property string                       $tier_label
 */
class JobPosition extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'core_org_job_positions';

    protected $fillable = [
        'title',
        'code',
        'tier',
        'image_url',
        'description',
        'is_active',
    ];

    protected $casts = [
        'tier'      => TierEnum::class,
        'is_active' => 'boolean',
    ];

    protected $appends = ['tier_label'];

    public function tierLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tier?->label() ?? ''
        );
    }

    protected static function newFactory(): JobPositionFactory
    {
        return JobPositionFactory::new();
    }
}
