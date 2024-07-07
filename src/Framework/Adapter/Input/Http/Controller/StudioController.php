<?php

declare(strict_types=1);

namespace App\Framework\Adapter\Input\Http\Controller;

use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Input\RegisterNewStudio\StudioDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudioController extends AbstractController
{
    public function __construct(
        private readonly RegisterNewStudioPort $registerNewStudioPort
    )
    {
    }

    #[Route('/studio', name: 'studio', methods: [Request::METHOD_POST])]
    public function handle(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $studioDTO = new StudioDTO(
            name: $data['name'],
            street: $data['street'],
            city: $data['city'],
            zipCode: $data['zipCode'],
            country: $data['country'],
            email: $data['email']
        );
        $studioDTO = $this->registerNewStudioPort->registerNewStudio($studioDTO);

        return new JsonResponse([
            'id' => $studioDTO->getId(),
            'name' => $studioDTO->getName(),
            'street' => $studioDTO->getStreet(),
            'city' => $studioDTO->getCity(),
            'zipCode' => $studioDTO->getZipCode(),
            'country' => $studioDTO->getCountry(),
            'email' => $studioDTO->getEmail()
        ], Response::HTTP_CREATED);
    }
}