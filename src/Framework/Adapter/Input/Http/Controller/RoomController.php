<?php

namespace App\Framework\Adapter\Input\Http\Controller;

use App\Application\Port\Input\RegisterNewRoom\EquipmentDTO;
use App\Application\Port\Input\RegisterNewRoom\RegisterNewRoomPort;
use App\Application\Port\Input\RegisterNewRoom\RoomDTO;
use App\Shared\Model\ValueObject\EquipmentType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RoomController extends AbstractController
{
    public function __construct(
        private readonly RegisterNewRoomPort $registerNewRoomPort,
        ValidatorInterface                   $validator,
    )
    {
        parent::__construct($validator);
    }

    #[Route('/rooms', name: 'create_room', methods: [Request::METHOD_POST])]
    public function handle(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $roomDTO = $this->createRoomDTO($data);
        $roomDTO = $this->registerNewRoomPort->registerNewRoom($roomDTO);

        return new JsonResponse($roomDTO->jsonSerialize(), Response::HTTP_CREATED);
    }

    private function createRoomDTO(mixed $data): RoomDTO
    {
        $equipments = new ArrayCollection();
        foreach ($data['equipments'] as $equipment) {
            $equipments->add(
                new EquipmentDTO(
                    name: $equipment['name'],
                    type: EquipmentType::fromString($equipment['type']),
                    serialNumber: $equipment['serialNumber']
                )
            );
        }

        $roomDTO = new RoomDTO(
            studioId: Uuid::fromString($data['studioId']),
            name: $data['name'],
            capacity: $data['capacity'],
            equipments: $equipments
        );

        $this->validate($roomDTO);

        return $roomDTO;
    }
}