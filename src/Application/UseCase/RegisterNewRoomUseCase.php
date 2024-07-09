<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Factory\EquipmentFactory;
use App\Application\Port\Input\RegisterNewRoom\RegisterNewRoomPort;
use App\Application\Port\Input\RegisterNewRoom\RoomDTO;
use App\Application\Port\Output\RoomRepositoryPort;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Capacity;
use App\Shared\Exception\ApplicationException;
use App\Shared\Exception\DomainException;

class RegisterNewRoomUseCase implements RegisterNewRoomPort
{
    public function __construct(
        private readonly StudioRepositoryPort $studioRepositoryPort,
        private readonly RoomRepositoryPort   $roomRepositoryPort,
        private readonly EquipmentFactory     $equipmentFactory
    )
    {
    }

    /**
     * Register a new room in a studio
     *
     * @throws DomainException If studio not found
     * @throws ApplicationException If studio not found
     */
    public function registerNewRoom(RoomDTO $roomDTO): RoomDTO
    {
        $studioAggregate = $this->getStudio($roomDTO);
        $room = $studioAggregate->registerNewRoom(
            name: $roomDTO->getName(),
            capacity: Capacity::create($roomDTO->getCapacity()),
            equipments: $this->equipmentFactory->createEquipmentCollectionFromDTO($roomDTO->getEquipments())
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
}