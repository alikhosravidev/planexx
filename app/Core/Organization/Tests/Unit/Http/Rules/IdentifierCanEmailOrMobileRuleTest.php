<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\Http\Rules;

use App\Core\Organization\Http\Rules\IdentifierCanEmailOrMobileRule;
use Tests\UnitTestBase;

class IdentifierCanEmailOrMobileRuleTest extends UnitTestBase
{
    private IdentifierCanEmailOrMobileRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new IdentifierCanEmailOrMobileRule();
    }

    public function testValidEmailPasses(): void
    {
        $failCalled = false;
        $failClosure = function ($message) use (&$failCalled) {
            $failCalled = true;
        };

        $this->rule->validate('identifier', 'test@example.com', $failClosure);

        $this->assertFalse($failCalled);
    }

    public function testValidMobilePasses(): void
    {
        $failCalled = false;
        $failClosure = function ($message) use (&$failCalled) {
            $failCalled = true;
        };

        $this->rule->validate('identifier', '09123456789', $failClosure);

        $this->assertFalse($failCalled);
    }

    public function testInvalidTypeFails(): void
    {
        $failMessage = null;
        $failClosure = function ($message) use (&$failMessage) {
            $failMessage = $message;
        };

        // Use a username which is neither email nor mobile
        $this->rule->validate('identifier', 'username123', $failClosure);

        $this->assertNotNull($failMessage);
    }

    public function testInvalidIdentifierFails(): void
    {
        $failMessage = null;
        $failClosure = function ($message) use (&$failMessage) {
            $failMessage = $message;
        };

        $this->rule->validate('identifier', '', $failClosure);

        $this->assertNotNull($failMessage);
    }
}
