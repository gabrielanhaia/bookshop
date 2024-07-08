<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Exception\StudioAlreadyExistsException;
use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Output;
use App\Application\Port\Shared\StudioDTO;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Address;
use App\Shared\Model\ValueObject\Email;

class RegisterNewStudioUseCase implements RegisterNewStudioPort
{
    public function __construct(
        private readonly Output\StudioRepositoryPort $studioRepository
    )
    {
    }

    /**
     * @throws StudioAlreadyExistsException if studio already exists
     */
    public function registerNewStudio(StudioDTO $studioDTO): StudioDTO
    {
        $this->validateIfStudioExists($studioDTO);
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

    /**
     * @throws StudioAlreadyExistsException if studio already exists
     */
    private function validateIfStudioExists(StudioDTO $studioDTO): void
    {
        $studioAggregate = $this->studioRepository
            ->findStudioByName($studioDTO->getName());

        if ($studioAggregate !== null) {
            throw new StudioAlreadyExistsException('Studio already exists');
        }
    }
}
