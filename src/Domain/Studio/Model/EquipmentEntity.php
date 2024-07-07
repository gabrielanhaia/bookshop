<?php

declare(strict_types=1);

namespace App\Domain\Studio\Model;

use App\Shared\Model\ValueObject\EquipmentType;
use Symfony\Component\Uid\Uuid;

class EquipmentEntity
{
    private function __construct(
        private readonly Uuid          $id,
        private readonly string        $name,
        private readonly EquipmentType $type,
        private readonly string        $serialNumber,
    )
    {
    }

    public static function create(
        string $name,
        EquipmentType $type,
        string $serialNumber
    ): self
    {
        return new self(Uuid::v7(), $name, $type, $serialNumber);
    }

    public static function createWithId(
        Uuid   $id,
        string $name,
        EquipmentType $type,
        string $serialNumber
    ): self
    {
        return new self($id, $name, $type, $serialNumber);
    }

    public function getId(): Uuid
    {
        return $this->id;
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