<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class AbstractTestCase extends TestCase
{
    protected Prophet $prophet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->prophet = new Prophet();
    }
}