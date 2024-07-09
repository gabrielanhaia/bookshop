<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Application\Port\Input\RegisterNewRoom\EquipmentDTO;
use App\Application\Port\Input\RegisterNewRoom\RoomDTO;
use App\Application\Port\Output\TransactionHandlerPort;
use App\Application\Port\Shared\StudioDTO;
use App\Domain\Studio\Model\EquipmentEntity;
use App\Domain\Studio\Model\RoomEntity;
use App\Domain\Studio\Model\StudioAggregate;
use App\Domain\Studio\Model\ValueObject\Address;
use App\Domain\Studio\Model\ValueObject\Capacity;
use App\ObjectSpecification\Transaction\TransactionHandlerInterface;
use App\Shared\Model\ValueObject\Email;
use App\Shared\Model\ValueObject\EquipmentType;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Uid\Uuid;

class AbstractTestCase extends TestCase
{
    protected const STUDIO_ID = '36edfa72-4322-4c3d-9f48-a2edb0c926e9';

    protected const STUDIO_NAME = 'Studio Name';

    protected const STUDIO_EMAIL = 'test@email.com';

    protected const STUDIO_STREET = 'Studio Street';

    protected const STUDIO_CITY = 'Studio City';

    protected const STUDIO_ZIP_CODE = 'Studio Zip Code';

    protected const STUDIO_COUNTRY = 'Studio Country';

    protected const ROOM_NAME = 'Room Name';

    protected const ROOM_CAPACITY = 10;

    protected const EQUIPMENT_NAME = 'Equipment Name';

    protected const EQUIPMENT_SERIAL_NUMBER = '123456';

    protected Prophet $prophet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->prophet = new Prophet();
    }

    public function mockTransactionHandlerPort(): TransactionHandlerPort
    {
        $mock = $this->prophet->prophesize(TransactionHandlerPort::class);
        $mock->execute(Argument::type('callable'))->will(function ($args) {
            return $args[0]();
        });

        return $mock->reveal();
    }

    protected function createStudioDTO(): StudioDTO
    {
        return StudioDTO::create(
            name: self::STUDIO_NAME,
            street: self::STUDIO_STREET,
            city: self::STUDIO_CITY,
            zipCode: self::STUDIO_ZIP_CODE,
            country: self::STUDIO_COUNTRY,
            email: self::STUDIO_EMAIL
        );
    }

    protected function createRoomDTO(): RoomDTO
    {
        return new RoomDTO(
            studioId: Uuid::fromString(self::STUDIO_ID),
            name: self::ROOM_NAME,
            capacity: self::ROOM_CAPACITY,
            equipments: new ArrayCollection([
                new EquipmentDTO(
                    name: self::EQUIPMENT_NAME,
                    type: EquipmentType::CAMERA,
                    serialNumber: self::EQUIPMENT_SERIAL_NUMBER
                )
            ])
        );
    }

    protected function createStudioAggregate(): StudioAggregate
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

    protected function createStudioAggregateWithRoom(): StudioAggregate
    {
        $studio = $this->createStudioAggregate();
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

        return $studio;
    }

    protected function createRoomEntity(): RoomEntity
    {
        return RoomEntity::create(
            studioId: Uuid::fromString(self::STUDIO_ID),
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
    }
}