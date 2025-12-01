<?php

declare(strict_types=1);

namespace App\Core\Organization\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\Organization\Database\Factories\UserFactory;
use App\Core\Organization\Enums\CustomerTypeEnum;
use App\Core\Organization\Enums\GenderEnum;
use App\Core\Organization\Enums\UserTypeEnum;
use App\Core\Organization\Services\Auth\ValueObjects\Identifier;
use App\Core\Organization\Traits\HasApiTokens;
use App\Core\Organization\Traits\HasJobPosition;
use App\ValueObjects\Email;
use App\ValueObjects\Mobile;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int                         $id
 * @property int|null                    $direct_manager_id
 * @property string                      $full_name
 * @property string                      $first_name
 * @property string                      $last_name
 * @property Mobile                      $mobile
 * @property UserTypeEnum                $user_type
 * @property CustomerTypeEnum|null       $customer_type
 * @property Email|null                  $email
 * @property string|null                 $national_code
 * @property GenderEnum|null             $gender
 * @property string|null                 $image_url
 * @property int|null                    $address_id
 * @property bool                        $is_active
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
    use SoftDeletes;

    protected $table = 'core_org_users';

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

    public function directManager(): BelongsTo
    {
        return $this->belongsTo(self::class, 'direct_manager_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(
            Department::class,
            'core_org_user_departments',
            'user_id',
            'department_id'
        )->withPivot('is_primary')->withTimestamps();
    }

    public function mobile(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => new Mobile($value),
            set: fn (string|Mobile $value) => (string) $value,
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? new Email($value) : null,
            set: fn (null|string|Email $value) => $value !== null ? (string) $value : null,
        );
    }

    public function verifyIdentifier(Identifier $identifier): self
    {
        if ($identifier->type->isMobile() && !$this->mobile_verified) {
            $this->mobile_verified_at = now();
        }

        if ($identifier->type->isEmail() && !$this->email_verified) {
            $this->email_verified_at = now();
        }

        return $this;
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
