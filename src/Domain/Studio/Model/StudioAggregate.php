<?php

declare(strict_types=1);

namespace App\Domain\Studio\Model;

use App\Domain\Shared\Model\ValueObject\Email;
use App\Domain\Studio\Model\ValueObject\Address;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Uuid;

class StudioAggregate
{
    /** @var ArrayCollection<RoomEntity> */
    private ArrayCollection $rooms;

    private function __construct(
        private readonly Uuid    $id,
        private readonly string  $name,
        private readonly Email  $email,
        private readonly Address $address,
        ?ArrayCollection         $rooms = null
    )
    {
        $this->rooms = new ArrayCollection();
        foreach ($rooms as $room) {
            $this->addRoom($room);
        }
    }

    public static function create(
        string          $name,
        Email           $email,
        Address         $address,
        ArrayCollection $rooms = null
    ): self
    {
        return new self(Uuid::v7(), $name, $email, $address, $rooms);
    }

    public static function createWithId(
        Uuid            $id,
        string          $name,
        Email           $email,
        Address         $address,
        ArrayCollection $rooms = null
    ): self
    {
        return new self($id, $name, $email, $address, $rooms);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getRooms(): ArrayCollection
    {
        return $this->rooms;
    }

    public function addRoom(RoomEntity $room): void
    {
        $this->rooms->add($room);
    }
}