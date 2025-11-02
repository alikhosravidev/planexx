<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\Http\Rules;

use App\Core\User\Http\Rules\IdentifierRule;
use Tests\UnitTestBase;

class IdentifierRuleTest extends UnitTestBase
{
    private IdentifierRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new IdentifierRule();
    }

    public function testValidIdentifierPasses(): void
    {
        $failCalled = false;
        $failClosure = function ($message) use (&$failCalled) {
            $failCalled = true;
        };

        // Use a valid email that should not throw exception
        $this->rule->validate('email', 'test@example.com', $failClosure);

        $this->assertFalse($failCalled);
    }

    public function testInvalidIdentifierFails(): void
    {
        $failMessage = null;
        $failClosure = function ($message) use (&$failMessage) {
            $failMessage = $message;
        };

        // Use invalid identifier that should throw exception
        $this->rule->validate('email', '', $failClosure);

        $this->assertNotNull($failMessage);
    }
}
