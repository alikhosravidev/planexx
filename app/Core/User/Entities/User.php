<?php

declare(strict_types=1);

namespace App\Core\User\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\Organization\Traits\HasJobPosition;
use App\Core\User\Database\Factories\UserFactory;
use App\Core\User\Enums\CustomerTypeEnum;
use App\Core\User\Enums\GenderEnum;
use App\Core\User\Enums\UserTypeEnum;
use App\Core\User\Traits\HasApiTokens;
use App\Query\ValueObjects\Email;
use App\Query\ValueObjects\Mobile;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int                          $id
 * @property int|null                     $direct_manager_id
 * @property string                       $full_name
 * @property string                       $first_name
 * @property string                       $last_name
 * @property Mobile                       $mobile
 * @property UserTypeEnum                 $user_type
 * @property CustomerTypeEnum|null        $customer_type
 * @property Email|null                   $email
 * @property string|null                  $national_code
 * @property GenderEnum|null              $gender
 * @property string|null                  $image_url
 * @property int|null                     $address_id
 * @property bool                         $is_active
 * @property \Carbon\Carbon|null         $birth_date
 * @property \Carbon\Carbon|null         $mobile_verified_at
 * @property \Carbon\Carbon|null         $email_verified_at
 * @property \Carbon\Carbon|null         $last_login_at
 * @property \Carbon\Carbon              $created_at
 * @property \Carbon\Carbon              $updated_at
 * @property \Carbon\Carbon|null         $deleted_at
 *
 * Relations:
 * @property Address                      $address
 */
class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasApiTokens;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasFactory;
    use HasRoles;
    use HasPermissions;
    use HasJobPosition;

    protected $fillable = [
        'direct_manager_id',
        'job_position_id',
        'address_id',
        'first_name',
        'last_name',
        'mobile',
        'user_type',
        'customer_type',
        'email',
        'national_code',
        'gender',
        'image_url',
        'is_active',
        'birth_date',
        'mobile_verified_at',
        'email_verified_at',
        'last_login_at',
    ];

    protected $casts = [
        'user_type'          => UserTypeEnum::class,
        'customer_type'      => CustomerTypeEnum::class,
        'gender'             => GenderEnum::class,
        'is_active'          => 'boolean',
        'birth_date'         => 'datetime',
        'mobile_verified_at' => 'datetime',
        'email_verified_at'  => 'datetime',
        'last_login_at'      => 'datetime',
    ];

    protected $hidden = ['password'];

    public function changePassword(string $password): self
    {
        $this->attributes['password'] = $password;

        return $this;
    }

    public function updateLastLogin(): self
    {
        $this->last_login_at = now();

        return $this;
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function mobile(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => new Mobile($value),
            set: fn (Mobile $value) => $value->value,
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? new Email($value) : null,
            set: fn (?Email $value) => $value?->value,
        );
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
