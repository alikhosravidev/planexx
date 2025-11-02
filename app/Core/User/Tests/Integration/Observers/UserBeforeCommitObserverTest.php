<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Integration\Observers;

use App\Core\User\Entities\User;
use Illuminate\Support\Facades\Hash;
use Tests\IntegrationTestBase;

class UserBeforeCommitObserverTest extends IntegrationTestBase
{
    public function testPasswordIsHashedWhenDirtyAndNotAlreadyHashed(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'plaintextpassword',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        // The observer should have hashed the password on creation
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('plaintextpassword', $user->fresh()->password));
    }

    public function testPasswordIsNotHashedWhenAlreadyHashed(): void
    {
        $hashedPassword = \Illuminate\Support\Facades\Hash::make('plaintextpassword');

        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => $hashedPassword,
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        // Password should remain the same since it's already hashed
        $this->assertEquals($hashedPassword, $user->fresh()->password);
    }

    public function testPasswordIsNotHashedWhenNotDirty(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'plaintextpassword',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        $originalPassword = $user->password;

        // Update something else, not password
        $user->first_name = 'Updated Name';
        $user->save();

        // Password should not change
        $this->assertEquals($originalPassword, $user->fresh()->password);
    }

    public function testPasswordIsHashedOnUpdateWhenDirty(): void
    {
        $user = \App\Core\User\Entities\User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '09123456789',
            'email' => 'john@example.com',
            'password' => 'oldpassword',
            'user_type' => \App\Core\User\Enums\UserTypeEnum::Customer,
            'is_active' => true,
        ]);

        $user->changePassword('newplaintextpassword');
        $user->save();

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newplaintextpassword', $user->fresh()->password));
    }
}
