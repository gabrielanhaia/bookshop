<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Exception\StudioAlreadyExistsException;
use App\Application\Factory\StudioFactory;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Application\UseCase\RegisterNewStudioUseCase;
use App\Domain\Studio\Model\StudioAggregate;
use App\Tests\Unit\AbstractTestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Uid\Uuid;

class RegisterNewStudioUseCaseTest extends AbstractTestCase
{
    private StudioRepositoryPort|ObjectProphecy|null $studioRepositoryPort;

    private StudioFactory|ObjectProphecy|null $studioFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studioRepositoryPort = $this->prophet->prophesize(StudioRepositoryPort::class);
        $this->studioFactory = $this->prophet->prophesize(StudioFactory::class);
    }

    public function testWhenStudioAlreadyExists(): void
    {
        $this->expectException(StudioAlreadyExistsException::class);

        $this->studioRepositoryPort
            ->findStudioByName(self::STUDIO_NAME)
            ->shouldBeCalledOnce()
            ->willReturn($this->createStudioAggregate());

        $useCase = $this->createUseCase();
        $useCase->registerNewStudio($this->createStudioDTO());
    }

    /**
     * @throws StudioAlreadyExistsException
     */
    public function testRegisterNewStudio(): void
    {
        $studioDTO = $this->createStudioDTO();
        $this->studioRepositoryPort
            ->findStudioByName(self::STUDIO_NAME)
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->studioFactory
            ->createStudioAggregateFromDTO($studioDTO)
            ->shouldBeCalledOnce()
            ->willReturn($this->createStudioAggregate());

        $studioAggregate = $this->createStudioAggregate();
        $this->studioRepositoryPort
            ->saveStudio(Argument::that(function (StudioAggregate $studio) use ($studioAggregate) {
                return $studio->getName() === $studioAggregate->getName()
                    && $studio->getEmail()->getValue() === $studioAggregate->getEmail()->getValue()
                    && $studio->getAddress()->getStreet() === $studioAggregate->getAddress()->getStreet()
                    && $studio->getAddress()->getCity() === $studioAggregate->getAddress()->getCity()
                    && $studio->getAddress()->getZipCode() === $studioAggregate->getAddress()->getZipCode()
                    && $studio->getAddress()->getCountry() === $studioAggregate->getAddress()->getCountry();
            }))
            ->shouldBeCalledOnce()
            ->willReturn($studioAggregate);

        $useCase = $this->createUseCase();
        $result = $useCase->registerNewStudio($studioDTO);
        $this->assertInstanceOf(Uuid::class, $result->getId());
    }

    private function createUseCase(): RegisterNewStudioUseCase
    {
        return new RegisterNewStudioUseCase($this->studioRepositoryPort->reveal(), $this->studioFactory->reveal());
    }
}