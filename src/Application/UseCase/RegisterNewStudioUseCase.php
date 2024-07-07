<?php

namespace App\Application\UseCase;

use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Input\RegisterNewStudio\StudioDTO;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Shared\Model\ValueObject\Email;
use App\Domain\Studio\Model\ValueObject\Address;

class RegisterNewStudioUseCase implements RegisterNewStudioPort
{
    public function __construct(
        private readonly StudioRepositoryPort $studioRepository
    )
    {
    }

    public function registerNewStudio(StudioDTO $studioDTO): StudioDTO
    {
        $email = new Email($studioDTO->getEmail());
        $address = new Address(
            street: $studioDTO->getStreet(),
            city: $studioDTO->getCity(),
            zipCode: $studioDTO->getZipCode(),
            country: $studioDTO->getCountry()
        );

        $studio = StudioAggregate::create(
            name: $studioDTO->getName(),
            email: $email,
            address: $address
        );

        $this->studioRepository->saveStudio($studio);

        return $studioDTO->setId($studio->getId());
    }
}
