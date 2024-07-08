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
    private const STUDIO_NAME = 'Studio 1';

    private const EMAIL = 'test@studio.com';

    private const ADDRESS_STREET = 'Studio 1 Street';

    private const ADDRESS_CITY = 'Studio 1 City';

    private const ADDRESS_ZIP_CODE = 'Studio 1 Zip Code';

    private const ADDRESS_COUNTRY = 'Studio 1 Country';

    private const ROOM_NAME = 'Room 1';

    private const ROOM_CAPACITY = 10;

    private const EQUIPMENT_NAME = 'Equipment 1';

    private const EQUIPMENT_SERIAL_NUMBER = '123456';

    public function testOpenNewStudios(): void
    {
        $studio = $this->createStudio();

        $this->assertEquals(self::STUDIO_NAME, $studio->getName());
        $this->assertEquals(self::EMAIL, $studio->getEmail()->getValue());
        $this->assertEquals(self::ADDRESS_STREET, $studio->getAddress()->getStreet());
        $this->assertEquals(self::ADDRESS_CITY, $studio->getAddress()->getCity());
        $this->assertEquals(self::ADDRESS_ZIP_CODE, $studio->getAddress()->getZipCode());
        $this->assertEquals(self::ADDRESS_COUNTRY, $studio->getAddress()->getCountry());
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
            email: new Email(self::EMAIL),
            address: new Address(
                street: self::ADDRESS_STREET,
                city: self::ADDRESS_CITY,
                zipCode: self::ADDRESS_ZIP_CODE,
                country: self::ADDRESS_COUNTRY
            ),
        );
    }
}