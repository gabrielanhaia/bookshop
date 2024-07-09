<?php

namespace App\Framework\Adapter\Output;

use App\Application\Port\Output\RoomRepositoryPort;
use App\Domain\Studio\Model\RoomEntity;
use Doctrine\DBAL\Connection;

class RoomMySQLRepository implements RoomRepositoryPort
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function saveRoom(RoomEntity $room): RoomEntity
    {
        $query = 'INSERT INTO rooms (id, studio_id, name, capacity) VALUES (?, ?, ?, ?)';
        $stmt = $this->connection->prepare($query);
        $stmt->executeQuery([
            $room->getId()->toRfc4122(),
            $room->getStudioId()->toRfc4122(),
            $room->getName(),
            $room->getCapacity()->getValue(),
        ]);

        // TODO: Logic to save equipments not implemented

        return $room;
    }

    public function findRoomByName(string $name): ?RoomEntity
    {
        // TODO: Implement findRoomByName() method.
        return null;
    }
}
