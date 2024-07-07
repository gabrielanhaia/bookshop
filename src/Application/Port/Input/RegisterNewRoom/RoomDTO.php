<?php

namespace App\Application\Port\Input\RegisterNewRoom;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Uuid;

class RoomDTO
{
    private ?Uuid $id = null;

    public function __construct(
        private readonly Uuid $studioId,
        private readonly string $name,
        private readonly int $capacity,
        private readonly ArrayCollection $equipments,
    )
    {
    }

    public function setId(Uuid $getId): self
    {
        $this->id = $getId;
        return $this;
    }

    public function getStudioId(): Uuid
    {
        return $this->studioId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getEquipments(): ArrayCollection
    {
        return $this->equipments;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}