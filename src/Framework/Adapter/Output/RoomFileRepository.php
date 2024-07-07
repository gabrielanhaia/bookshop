<?php

namespace App\Framework\Adapter\Output;

use App\Application\Port\Output\RoomRepositoryPort;
use App\Domain\Studio\Model\RoomEntity;

class RoomFileRepository implements RoomRepositoryPort
{

    public function saveRoom(RoomEntity $room): RoomEntity
    {
        // TODO: Implement saveRoom() method.
    }

    public function findRoomByName(string $name): ?RoomEntity
    {
        // TODO: Implement findRoomByName() method.
    }
}