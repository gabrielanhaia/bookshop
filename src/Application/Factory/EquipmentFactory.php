<?php

namespace App\Application\Factory;

use App\Application\Port\Input\RegisterNewRoom\EquipmentDTO;
use App\Domain\Studio\Model\EquipmentEntity;
use Doctrine\Common\Collections\ArrayCollection;

class EquipmentFactory
{
    /**
     * @param ArrayCollection<EquipmentDTO> $equipmentsDTO
     * @return ArrayCollection<EquipmentEntity>
     */
    public function createEquipmentCollectionFromDTO(ArrayCollection $equipmentsDTO): ArrayCollection
    {
        $equipments = new ArrayCollection();
        foreach ($equipmentsDTO as $equipmentDTO) {
            $equipments->add($this->createEquipmentFromDTO($equipmentDTO));
        }

        return $equipments;
    }

    public function createEquipmentFromDTO(EquipmentDTO $equipmentDTO): EquipmentEntity
    {
        return EquipmentEntity::create(
            name: $equipmentDTO->getName(),
            type: $equipmentDTO->getType(),
            serialNumber: $equipmentDTO->getSerialNumber()
        );
    }
}