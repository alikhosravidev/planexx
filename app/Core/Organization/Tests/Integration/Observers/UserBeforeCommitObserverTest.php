<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Integration\Observers;

use App\Core\Organization\Entities\User;
use App\ValueObjects\Email;
use App\ValueObjects\Mobile;
use Illuminate\Support\Facades\Hash;
use Tests\IntegrationTestBase;

class UserBeforeCommitObserverTest extends IntegrationTestBase
{
    public function test_password_is_hashed_when_dirty_and_not_already_hashed(): void
    {
        $user = User::factory()->create(
            [
                'email'    => new Email('john@example.com'),
                'mobile'   => new Mobile('09123456789'),
                'password' => 'plaintextpassword',
            ]
        );

        // The observer should have hashed the password on creation
        $this->assertTrue(Hash::check('plaintextpassword', $user->fresh()->password));
    }

    public function test_password_is_not_hashed_when_already_hashed(): void
    {
        $hashedPassword = Hash::make('plaintextpassword');

        $user = User::factory()->create(
            [
                'email'    => new Email('john@example.com'),
                'mobile'   => new Mobile('09123456789'),
                'password' => $hashedPassword,
            ]
        );

        // Password should remain the same since it's already hashed
        $this->assertEquals($hashedPassword, $user->fresh()->password);
    }

    public function test_password_is_not_hashed_when_not_dirty(): void
    {
        $user = User::factory()->create(
            [
                'email'    => new Email('john@example.com'),
                'mobile'   => new Mobile('09123456789'),
                'password' => 'plaintextpassword',
            ]
        );

        $originalPassword = $user->password;

        // Update something else, not password
        $user->first_name = 'Updated Name';
        $user->save();

        // Password should not change
        $this->assertEquals($originalPassword, $user->fresh()->password);
    }

    public function test_password_is_hashed_on_update_when_dirty(): void
    {
        $user = User::factory()->create(
            [
                'email'    => new Email('john@example.com'),
                'mobile'   => new Mobile('09123456789'),
                'password' => 'oldpassword',
            ]
        );

        $user->changePassword('newplaintextpassword');
        $user->save();

        $this->assertTrue(Hash::check('newplaintextpassword', $user->fresh()->password));
    }
}
