<?php

declare(strict_types=1);

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Symfony\Component\Uid\Uuid;

class DomainTest
{
    public function testDomainDoesNotDependOnOtherLayers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Domain'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Framework'),
                Selector::inNamespace('App\Application')
            )->because('Domain layer should not depend on any other layer');
    }

    public function testDomainDoesNotDependOnSymfony(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Domain'))
            ->shouldNotDependOn()
            ->classes(Selector::inNamespace('Symfony'))
            ->excluding(Selector::classname(Uuid::class))
            ->because('Domain layer should not depend on any framework');
    }
}