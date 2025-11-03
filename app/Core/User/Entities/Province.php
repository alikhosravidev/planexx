<?php

declare(strict_types=1);

namespace App\Core\User\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\User\Database\Factories\ProvinceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int         $id
 * @property int|null    $country_id
 * @property string      $name
 * @property string|null $name_en
 * @property float|null  $latitude
 * @property float|null  $longitude
 *
 * Relations:
 * @property Country     $country
 */
class Province extends BaseModel
{
    use HasFactory;
    protected $table    = 'location_provinces';
    public $timestamps  = false;
    protected $perPage  = 20;
    protected $fillable = [
        'country_id',
        'name',
        'name_en',
        'latitude',
        'longitude',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    protected static function newFactory(): ProvinceFactory
    {
        return ProvinceFactory::new();
    }
}
