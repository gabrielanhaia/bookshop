<?php

namespace App\Tests\Unit\Domain\Studio\Model;

use App\Domain\Studio\Model\EquipmentEntity;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Address;
use App\Domain\Studio\Model\ValueObject\Capacity;
use App\Shared\Model\ValueObject\Email;
use App\Shared\Model\ValueObject\EquipmentType;
use App\Tests\Unit\AbstractTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class StudioAggregateTest extends AbstractTestCase
{
    public function testOpenNewStudios(): void
    {
        $studio = $this->createStudio();

        $this->assertEquals(self::STUDIO_NAME, $studio->getName());
        $this->assertEquals(self::STUDIO_EMAIL, $studio->getEmail()->getValue());
        $this->assertEquals(self::STUDIO_STREET, $studio->getAddress()->getStreet());
        $this->assertEquals(self::STUDIO_CITY, $studio->getAddress()->getCity());
        $this->assertEquals(self::STUDIO_ZIP_CODE, $studio->getAddress()->getZipCode());
        $this->assertEquals(self::STUDIO_COUNTRY, $studio->getAddress()->getCountry());
    }

    public function testRegisterNewRoomToStudio(): void
    {
        $studio = $this->createStudio();
        $studio->registerNewRoom(
            name: self::ROOM_NAME,
            capacity: Capacity::create(self::ROOM_CAPACITY),
            equipments: new ArrayCollection([
                EquipmentEntity::create(
                    name: self::EQUIPMENT_NAME,
                    type: EquipmentType::CAMERA,
                    serialNumber: self::EQUIPMENT_SERIAL_NUMBER
                )
            ])
        );

        $this->assertCount(1, $studio->getRooms());
        $this->assertEquals(self::ROOM_NAME, $studio->getRooms()->first()->getName());
        $this->assertEquals(self::ROOM_CAPACITY, $studio->getRooms()->first()->getCapacity()->getValue());
        $this->assertCount(1, $studio->getRooms()->first()->getEquipments());
        $this->assertEquals(self::EQUIPMENT_NAME, $studio->getRooms()->first()->getEquipments()->first()->getName());
        $this->assertEquals(EquipmentType::CAMERA, $studio->getRooms()->first()->getEquipments()->first()->getType());
        $this->assertEquals(self::EQUIPMENT_SERIAL_NUMBER, $studio->getRooms()->first()->getEquipments()->first()->getSerialNumber());
    }

    private function createStudio(): StudioAggregate
    {
        return StudioAggregate::openNewStudio(
            name: self::STUDIO_NAME,
            email: new Email(self::STUDIO_EMAIL),
            address: new Address(
                street: self::STUDIO_STREET,
                city: self::STUDIO_CITY,
                zipCode: self::STUDIO_ZIP_CODE,
                country: self::STUDIO_COUNTRY
            ),
        );
    }
}