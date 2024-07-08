<?php

namespace App\Tests\Architecture;

use PHPUnit\Framework\TestCase;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Symfony\Component\Uid\Uuid;

class ApplicationTest extends TestCase
{
    public function testApplicationDoesNotDependOnFrameworkLayer(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application'))
            ->shouldNotDependOn()
            ->classes(Selector::inNamespace('App\Framework'))
            ->because('Application layer should not depend on the Framework layer');
    }

    public function testApplicationDoesNotDependOnSymfony(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application'))
            ->shouldNotDependOn()
            ->classes(Selector::inNamespace('Symfony'))
            ->excluding(Selector::classname(Uuid::class))
            ->because('Application layer should not depend on any framework');
    }

    public function testPortsShouldBeInterfaces(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application\Port'))
            ->excluding(Selector::classname('|DTO|', true))
            ->shouldBeInterface()
            ->because('Ports should be interfaces');
    }

    public function testInputPortsShouldHaveOnlyOneMethod(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Application\Port\Input'))
            ->excluding(Selector::classname('|DTO|', true))
            ->shouldHaveOnlyOnePublicMethod()
            ->because('Input ports should have only one method definition');
    }
}