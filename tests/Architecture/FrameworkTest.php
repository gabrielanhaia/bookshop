<?php

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use PHPUnit\Framework\TestCase;

class FrameworkTest extends TestCase
{
    public function testOutputAdaptersShouldImplementPorts(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Framework\Adapter\Output'))
            ->shouldImplement()
            ->classes(Selector::inNamespace('App\Application\Port\Output'))
            ->because('Output adapters should implement output ports');
    }
}