<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Port\Input\RegisterNewRoom\EquipmentDTO;
use App\Application\Port\Input\RegisterNewRoom\RegisterNewRoomPort;
use App\Application\Port\Input\RegisterNewRoom\RoomDTO;
use App\Application\Port\Output\RoomRepositoryPort;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Domain\Studio\Model\EquipmentEntity;
use App\Domain\Studio\Model\RoomEntity;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Capacity;
use App\Shared\Exception\ApplicationException;
use Doctrine\Common\Collections\ArrayCollection;

class RegisterNewRoomUseCase implements RegisterNewRoomPort
{
    public function __construct(
        private readonly StudioRepositoryPort $studioRepositoryPort,
        private readonly RoomRepositoryPort $roomRepositoryPort
    )
    {
    }

    /**
     * @throws ApplicationException if studio not found or room already exists
     */
    public function registerNewRoom(RoomDTO $roomDTO): RoomDTO
    {
        $studioAggregate = $this->getStudio($roomDTO);
        $this->validateDuplicatedRooms($roomDTO);

        $room = $studioAggregate->registerNewRoom(
            name: $roomDTO->getName(),
            capacity: Capacity::create($roomDTO->getCapacity()),
            equipments: $this->formatEquipments($roomDTO),
        );

        $room = $this->roomRepositoryPort->saveRoom($room);

        return $roomDTO->setId($room->getId());
    }

    /**
     * @throws ApplicationException If studio not found
     */
    private function getStudio(RoomDTO $roomDTO): StudioAggregate
    {
        $studioAggregate = $this->studioRepositoryPort->findStudioById($roomDTO->getStudioId());
        if ($studioAggregate === null) {
            throw new ApplicationException('Studio not found');
        }

        return $studioAggregate;
    }

    /**
     * @throws ApplicationException If room already exists
     */
    private function validateDuplicatedRooms(RoomDTO $roomDTO): void
    {
        $room = $this->roomRepositoryPort->findRoomByName($roomDTO->getName());
        if ($room !== null) {
            throw new ApplicationException('Room already exists');
        }
    }

    /**
     * @param RoomDTO $roomDTO
     * @return ArrayCollection<EquipmentEntity>
     */
    private function formatEquipments(RoomDTO $roomDTO): ArrayCollection
    {

        $equipments = new ArrayCollection();
        /** @var EquipmentDTO $equipment */
        foreach ($roomDTO->getEquipments() as $equipment) {
            $equipments->add(
                EquipmentEntity::create(
                    name: $equipment->getName(),
                    type: $equipment->getType(),
                    serialNumber: $equipment->getSerialNumber()
                )
            );
        }

        return $equipments;
    }
}