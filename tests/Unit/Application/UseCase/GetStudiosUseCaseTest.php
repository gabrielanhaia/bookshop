<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Port\Output\StudioRepositoryPort;
use App\Application\Port\Shared\StudioDTOCollection;
use App\Application\UseCase\GetStudiosUseCase;
use App\Tests\Unit\AbstractTestCase;
use Prophecy\Prophecy\ObjectProphecy;

class GetStudiosUseCaseTest extends AbstractTestCase
{
    private StudioRepositoryPort|ObjectProphecy|null $studioRepositoryPort;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studioRepositoryPort = $this->prophet->prophesize(StudioRepositoryPort::class);
    }

    public function testGetStudios(): void
    {
        $response = new StudioDTOCollection();
        $this->studioRepositoryPort
            ->getAllStudios()
            ->shouldBeCalledOnce()
            ->willReturn($response);

        $useCase = new GetStudiosUseCase($this->studioRepositoryPort->reveal());
        $this->assertEquals($response, $useCase->getStudios());
    }
}