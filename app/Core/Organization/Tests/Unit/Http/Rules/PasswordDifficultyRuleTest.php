<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\Http\Rules;

use App\Core\Organization\Http\Rules\PasswordDifficultyRule;
use App\Core\Organization\Services\Auth\DTOs\PasswordConfig;
use Tests\UnitTestBase;

class PasswordDifficultyRuleTest extends UnitTestBase
{
    public function testValidPasswordPasses(): void
    {
        $config = new PasswordConfig('^.{6,}');
        $rule = new PasswordDifficultyRule($config);

        $failCalled = false;
        $failClosure = function ($message) use (&$failCalled) {
            $failCalled = true;
        };

        $rule->validate('password', '123456', $failClosure);

        $this->assertFalse($failCalled);
    }

    public function testInvalidPasswordFails(): void
    {
        $config = new PasswordConfig('^.{6,}');
        $rule = new PasswordDifficultyRule($config);

        $failMessage = null;
        $failClosure = function ($message) use (&$failMessage) {
            $failMessage = $message;
        };

        $rule->validate('password', '123', $failClosure);

        $this->assertEquals('پسورد حداقل باید حاوی ۶ حرف باشد.', $failMessage);
    }

    public function testPasswordFailMessageForDifferentRegex(): void
    {
        $testCases = [
            ['^.{6,}', 'پسورد حداقل باید حاوی ۶ حرف باشد.'],
            ['^.{8,}', 'پسورد حداقل باید حاوی ۸ حرف باشد.'],
            ['^(?=.*[a-z|A-Z])(?=.*[0-9]).{8,}', 'پسورد باید حاوی حروف و اعداد و حداقل ۸ حرف داشته باشد.'],
            ['^(?=.*[a-z|A-Z])(?=.*[0-9])(?=.*[\W]).{8,}', 'پسورد باید حاوی حروف و اعداد و کاراکترهای خاص و حداقل ۸ حرف داشته باشد.'],
            ['unknown', 'پسورد وارد شده ضعیف می باشد.'],
        ];

        foreach ($testCases as [$regex, $expectedMessage]) {
            $config = new PasswordConfig($regex);
            $rule = new PasswordDifficultyRule($config);

            $failMessage = null;
            $failClosure = function ($message) use (&$failMessage) {
                $failMessage = $message;
            };

            $rule->validate('password', '123', $failClosure);

            $this->assertEquals($expectedMessage, $failMessage, "Failed for regex: $regex");
        }
    }
}
