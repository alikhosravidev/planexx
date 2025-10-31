<?php

declare(strict_types=1);

namespace App\Core\User\Entities;

use App\Contracts\Model\BaseModel;
use App\Core\User\Database\Factories\UserFactory;
use App\Core\User\Traits\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use HasApiTokens;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasFactory;

    protected $fillable = [
        'full_name',
        'mobile',
        'email',
        'username',
        'email_verified_at',
        'mobile_verified_at',
    ];

    protected array $dates = [
        'email_verified_at',
        'mobile_verified_at',
    ];

    protected $hidden = ['password'];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

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
}
