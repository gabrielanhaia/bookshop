<?php

namespace App\Application\Port\Input\RegisterNewRoom;

interface RegisterNewRoomPort
{
    public function registerNewRoom(RoomDTO $roomDTO): RoomDTO;
}
