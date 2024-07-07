<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Port\Input\RegisterNewRoom\RegisterNewRoomPort;
use App\Application\Port\Input\RegisterNewRoom\RoomDTO;
use App\Application\Port\Input\RegisterNewStudio\EquipmentDTO;
use App\Application\Port\Output\RoomRepositoryPort;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Domain\Studio\Model\EquipmentEntity;
use App\Domain\Studio\Model\RoomEntity;
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
        $this->validateRoomAndStudio($roomDTO);

        $roomAggregate = RoomEntity::registerNewRoom(
            studioId: $roomDTO->getStudioId(),
            name: $roomDTO->getName(),
            capacity: Capacity::create($roomDTO->getCapacity()),
            equipments: $this->formatEquipments($roomDTO),
        );

        $roomAggregate = $this->roomRepositoryPort->saveRoom($roomAggregate);

        return $roomDTO->setId($roomAggregate->getId());
    }

    /**
     * @throws ApplicationException if studio not found or room already exists
     */
    private function validateRoomAndStudio(RoomDTO $roomDTO): void
    {
        $studio = $this->studioRepositoryPort->findStudioById($roomDTO->getStudioId());
        if ($studio === null) {
            throw new ApplicationException('Studio not found');
        }

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