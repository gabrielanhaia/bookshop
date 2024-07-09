<?php

namespace App\Tests\Unit\Application\UseCase;

use App\Application\Port\Output\RoomRepositoryPort;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Application\UseCase\RegisterNewRoomUseCase;
use App\Domain\Studio\Model\RoomEntity;
use App\Shared\Exception\ApplicationException;
use App\Shared\Model\ValueObject\EquipmentType;
use App\Tests\Unit\AbstractTestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Uid\Uuid;

class RegisterNewRoomUseCaseTest extends AbstractTestCase
{
    private StudioRepositoryPort|ObjectProphecy|null $studioRepositoryPort;

    private RoomRepositoryPort|ObjectProphecy|null $roomRepositoryPort;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studioRepositoryPort = $this->prophet->prophesize(StudioRepositoryPort::class);
        $this->roomRepositoryPort = $this->prophet->prophesize(RoomRepositoryPort::class);
    }

    public function testWhenStudioNotFoundThenThrowException(): void
    {
        $this->expectException(ApplicationException::class);

        $this->studioRepositoryPort
            ->findStudioById(Uuid::fromString(self::STUDIO_ID))
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->roomRepositoryPort->saveRoom(Argument::any())->shouldNotBeCalled();

        $this->createUseCase()
            ->registerNewRoom($this->createRoomDTO());
    }

    public function testSuccessfullyRegisterNewRoom(): void
    {
        $this->studioRepositoryPort
            ->findStudioById(Uuid::fromString(self::STUDIO_ID))
            ->shouldBeCalledOnce()
            ->willReturn($this->createStudioAggregate());

        $this->roomRepositoryPort
            ->findRoomByName(self::ROOM_NAME)
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->roomRepositoryPort
            ->saveRoom(Argument::that(function (RoomEntity $room) {
                $equipment = $room->getEquipments()->first();

                return $room->getName() === self::ROOM_NAME
                    && $room->getCapacity()->getValue() === self::ROOM_CAPACITY
                    && $equipment->getSerialNumber() === self::EQUIPMENT_SERIAL_NUMBER
                    && $equipment->getName() === self::EQUIPMENT_NAME
                    && $equipment->getType() === EquipmentType::CAMERA;

            }))
            ->shouldBeCalledOnce()
            ->willReturn($this->createRoomEntity());

        $useCase = $this->createUseCase();
        $roomDTO = $useCase->registerNewRoom($this->createRoomDTO());
        $this->assertEquals(self::STUDIO_ID, $roomDTO->getStudioId()->toRfc4122());
    }

    private function createUseCase(): RegisterNewRoomUseCase
    {
        return new RegisterNewRoomUseCase(
            $this->studioRepositoryPort->reveal(),
            $this->roomRepositoryPort->reveal()
        );
    }
}