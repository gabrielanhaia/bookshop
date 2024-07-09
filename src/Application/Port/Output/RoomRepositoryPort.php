<?php

namespace App\Application\Port\Output;

use App\Domain\Studio\Model\RoomEntity;

interface RoomRepositoryPort
{
    public function saveRoom(RoomEntity $room): RoomEntity;

    public function findRoomByName(string $name): ?RoomEntity;
}
