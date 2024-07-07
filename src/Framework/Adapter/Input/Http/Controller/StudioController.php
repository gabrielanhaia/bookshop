<?php

declare(strict_types=1);

namespace App\Framework\Adapter\Input\Http\Controller;

use App\Application\Exception\StudioAlreadyExistsException;
use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Input\RegisterNewStudio\StudioDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
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

        try {
            $studioDTO = $this->registerNewStudioPort->registerNewStudio($studioDTO);
        } catch (StudioAlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        return new JsonResponse($studioDTO->jsonSerialize(), Response::HTTP_CREATED);
    }
}