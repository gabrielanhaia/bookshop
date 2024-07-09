<?php

namespace App\Application\Factory;

use App\Application\Port\Shared\StudioDTO;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Address;
use App\Shared\Model\ValueObject\Email;

class StudioFactory
{
    public function createStudioAggregateFromDTO(StudioDTO $studioDTO): StudioAggregate
    {
        return StudioAggregate::openNewStudio(
            name: $studioDTO->getName(),
            email: new Email($studioDTO->getEmail()),
            address: new Address(
                street: $studioDTO->getStreet(),
                city: $studioDTO->getCity(),
                zipCode: $studioDTO->getZipCode(),
                country: $studioDTO->getCountry()
            )
        );
    }
}
