<?php

declare(strict_types=1);

namespace App\Domain\Studio\Model;

use App\Domain\Studio\Model\ValueObject\Capacity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Uuid;

class RoomEntity
{
    /** @var ArrayCollection<EquipmentEntity> */
    private ArrayCollection $equipments;

    private function __construct(
        private readonly Uuid     $id,
        private readonly Uuid     $studioId,
        private readonly string   $name,
        private readonly Capacity $capacity,
        ?ArrayCollection          $equipments = null
    )
    {
        $this->equipments = new ArrayCollection();

        foreach ($equipments as $equipment) {
            $this->addEquipment($equipment);
        }
    }

    public static function create(
        Uuid            $studioId,
        string          $name,
        Capacity        $capacity,
        ArrayCollection $equipments = null
    ): self
    {
        return new self(Uuid::v7(), $studioId, $name, $capacity, $equipments);
    }

    public static function createWithId(
        Uuid            $id,
        Uuid            $studioId,
        string          $name,
        Capacity        $capacity,
        ArrayCollection $equipments = null
    ): self
    {
        return new self($id, $studioId, $name, $capacity, $equipments);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCapacity(): Capacity
    {
        return $this->capacity;
    }

    public function addEquipment(EquipmentEntity $equipment): void
    {
        $this->equipments->add($equipment);
    }

    /**
     * @return ArrayCollection<EquipmentEntity>
     */
    public function getEquipments(): ArrayCollection
    {
        return $this->equipments;
    }

    public function getStudioId(): Uuid
    {
        return $this->studioId;
    }
}