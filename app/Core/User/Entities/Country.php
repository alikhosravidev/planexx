<?php

declare(strict_types=1);

namespace App\Core\User\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\User\Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int         $id
 * @property string|null $name
 * @property string|null $name_en
 *
 * Relations:
 * @property HasMany     $addresses
 */
class Country extends BaseModel
{
    use HasFactory;
    protected $table    = 'location_countries';
    public $timestamps  = false;
    protected $perPage  = 20;
    protected $fillable = [
        'name',
        'name_en',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
