<?php

declare(strict_types=1);

namespace Tests;

/**
 * Base class for Unit tests that need Laravel container but NOT database.
 * For pure unit tests without any Laravel dependencies, extend PHPUnit\Framework\TestCase directly.
 */
abstract class UnitTestBase extends TestCase
{
}
