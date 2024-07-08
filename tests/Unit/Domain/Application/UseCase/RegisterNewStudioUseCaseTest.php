<?php

namespace App\Tests\Unit\Domain\Application\UseCase;

use App\Application\Exception\StudioAlreadyExistsException;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Application\Port\Shared\StudioDTO;
use App\Application\UseCase\RegisterNewStudioUseCase;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Address;
use App\Shared\Model\ValueObject\Email;
use App\Tests\Unit\AbstractTestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Uid\Uuid;

class RegisterNewStudioUseCaseTest extends AbstractTestCase
{
    private const STUDIO_NAME = 'Studio Name';

    private const STUDIO_EMAIL = 'test@studio.com';

    private const STUDIO_STREET = 'Studio Street';

    private const STUDIO_CITY = 'Studio City';

    private const STUDIO_ZIP_CODE = 'Studio Zip Code';

    private const STUDIO_COUNTRY = 'Studio Country';

    private StudioRepositoryPort|ObjectProphecy|null $studioRepositoryPort;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studioRepositoryPort = $this->prophet->prophesize(StudioRepositoryPort::class);
    }

    public function testWhenStudioAlreadyExists(): void
    {
        $this->expectException(StudioAlreadyExistsException::class);

        $this->studioRepositoryPort
            ->findStudioByName(self::STUDIO_NAME)
            ->shouldBeCalledOnce()
            ->willReturn($this->createStudioAggregate());

        $useCase = $this->buildUseCase();
        $useCase->registerNewStudio($this->createStudioDTO());
    }

    /**
     * @throws StudioAlreadyExistsException
     */
    public function testRegisterNewStudio(): void
    {
        $this->studioRepositoryPort
            ->findStudioByName(self::STUDIO_NAME)
            ->shouldBeCalledOnce()
            ->willReturn(null);

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

        $useCase = $this->buildUseCase();
        $studioDTO = $useCase->registerNewStudio($this->createStudioDTO());
        $this->assertInstanceOf(Uuid::class, $studioDTO->getId());
    }

    private function createStudioDTO(): StudioDTO
    {
        return StudioDTO::create(
            name: self::STUDIO_NAME,
            street: self::STUDIO_STREET,
            city: self::STUDIO_CITY,
            zipCode: self::STUDIO_ZIP_CODE,
            country: self::STUDIO_COUNTRY,
            email: self::STUDIO_EMAIL
        );
    }

    private function createStudioAggregate(): StudioAggregate
    {
        return StudioAggregate::openNewStudio(
            name: self::STUDIO_NAME,
            email: new Email(self::STUDIO_EMAIL),
            address: new Address(
                street: self::STUDIO_STREET,
                city: self::STUDIO_CITY,
                zipCode: self::STUDIO_ZIP_CODE,
                country: self::STUDIO_COUNTRY
            ),
        );
    }

    private function buildUseCase(): RegisterNewStudioUseCase
    {
        return new RegisterNewStudioUseCase($this->studioRepositoryPort->reveal());
    }
}