<?php

declare(strict_types=1);

namespace App\Framework\Adapter\Input\Http\Controller;

use App\Application\Exception\StudioAlreadyExistsException;
use App\Application\Port\Input\GetStudios\GetStudiosPort;
use App\Application\Port\Input\RegisterNewStudio\RegisterNewStudioPort;
use App\Application\Port\Shared\StudioDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StudioController extends AbstractController
{
    public function __construct(
        private readonly RegisterNewStudioPort $registerNewStudioPort,
        private readonly GetStudiosPort        $getStudiosPort,
        ValidatorInterface                     $validator,
    )
    {
        parent::__construct($validator);
    }

    #[Route('/studios', name: 'create_studio', methods: [Request::METHOD_POST])]
    public function handle(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $studioDTO = StudioDTO::create(
            name: $data['name'],
            street: $data['street'],
            city: $data['city'],
            zipCode: $data['zipCode'],
            country: $data['country'],
            email: $data['email']
        );
        $this->validate($studioDTO);

        try {
            $studioDTO = $this->registerNewStudioPort->registerNewStudio($studioDTO);
        } catch (StudioAlreadyExistsException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        return new JsonResponse($studioDTO->jsonSerialize(), Response::HTTP_CREATED);
    }

    #[Route('/studios', name: 'get_studios', methods: [Request::METHOD_GET])]
    public function getStudios(): Response
    {
        $studios = $this->getStudiosPort->getStudios();

        return new JsonResponse($studios, Response::HTTP_OK);
    }
}