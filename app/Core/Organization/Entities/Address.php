<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\Organization\Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int         $id
 * @property int         $user_id
 * @property int|null    $country_id
 * @property int|null    $province_id
 * @property int|null    $city_id
 * @property string      $receiver_name
 * @property string|null $receiver_mobile
 * @property string|null $address
 * @property string|null $postal_code
 * @property float|null  $latitude
 * @property float|null  $longitude
 * @property bool        $is_verified
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * Relations:
 * @property User        $user
 * @property Country     $country
 * @property Province    $province
 * @property City        $city
 */
class Address extends BaseModel
{
    use HasFactory;

    protected $table    = 'core_org_addresses';
    protected $perPage  = 20;
    protected $fillable = [
        'user_id',
        'country_id',
        'province_id',
        'city_id',
        'receiver_name',
        'receiver_mobile',
        'address',
        'postal_code',
        'latitude',
        'longitude',
        'is_verified',
    ];

    public array $protectedFields = [
        'receiver_name',
        'receiver_mobile',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }
}
