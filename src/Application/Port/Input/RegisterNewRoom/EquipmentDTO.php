<?php

namespace App\Application\Port\Input\RegisterNewRoom;

use App\Shared\Model\ValueObject\EquipmentType;

class EquipmentDTO
{
    public function __construct(
        private readonly string $name,
        private readonly EquipmentType $type,
        private readonly string $serialNumber,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): EquipmentType
    {
        return $this->type;
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }
}
