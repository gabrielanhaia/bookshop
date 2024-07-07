<?php

namespace App\Framework\Adapter\Output;

use App\Application\Port\Output\StudioRepositoryPort;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Address;
use App\Shared\Model\ValueObject\Email;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\Uid\Uuid;

class StudioFileRepository implements StudioRepositoryPort
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }

    /**
     * @throws Exception
     */
    public function saveStudio(StudioAggregate $studio): StudioAggregate
    {
        $query = 'INSERT INTO studios (id, name, email, street, city, zip_code, country) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->connection->prepare($query);
        $stmt->executeQuery([
            $studio->getId()->toRfc4122(),
            $studio->getName(),
            $studio->getEmail()->getValue(),
            $studio->getAddress()->getStreet(),
            $studio->getAddress()->getCity(),
            $studio->getAddress()->getZipCode(),
            $studio->getAddress()->getCountry(),
        ]);

        return $studio;
    }

    public function findStudioByName(string $name): ?StudioAggregate
    {
        $query = 'SELECT * FROM studios WHERE name = ?';
        $stmt = $this->connection->prepare($query);
        $stmtResult = $stmt->executeQuery([$name]);

        $result = $stmtResult->fetchAssociative();
        if ($result === false) {
            return null;
        }

        return StudioAggregate::createWithId(
            id: Uuid::fromString($result['id']),
            name: $result['name'],
            email: new Email($result['email']),
            address: new Address(
                street: $result['street'],
                city: $result['city'],
                zipCode: $result['zip_code'],
                country: $result['country']
            )
        );

    }

    public function findStudioById(Uuid $getStudioId): ?StudioAggregate
    {
        // TODO: Implement findStudioByNameId() method.
    }
}