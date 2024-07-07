<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Input\RegisterNewStudio\StudioDTO;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Address;
use App\Shared\Exception\ApplicationException;
use App\Shared\Model\ValueObject\Email;

class RegisterNewStudioUseCase implements RegisterNewStudioPort
{
    public function __construct(
        private readonly StudioRepositoryPort $studioRepository
    )
    {
    }

    /**
     * @throws ApplicationException if studio already exists
     */
    public function registerNewStudio(StudioDTO $studioDTO): StudioDTO
    {
        $studioAggregate = $this->studioRepository->findStudioByName($studioDTO->getName());

        if ($studioAggregate !== null) {
            throw new ApplicationException('Studio already exists');
        }

        $studioAggregate = StudioAggregate::openNewStudio(
            name: $studioDTO->getName(),
            email: new Email($studioDTO->getEmail()),
            address: new Address(
                street: $studioDTO->getStreet(),
                city: $studioDTO->getCity(),
                zipCode: $studioDTO->getZipCode(),
                country: $studioDTO->getCountry()
            )
        );

        $this->studioRepository->saveStudio($studioAggregate);

        return $studioDTO->setId($studioAggregate->getId());
    }
}
