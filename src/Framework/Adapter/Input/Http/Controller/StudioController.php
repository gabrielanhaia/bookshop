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
use OpenApi\Attributes as OA;

class StudioController extends AbstractController
{
    public function __construct(
        private readonly RegisterNewStudioPort $registerNewStudioPort,
        private readonly GetStudiosPort        $getStudiosPort,
        ValidatorInterface                     $validator,
    ) {
        parent::__construct($validator);
    }

    #[Route('/studios', name: 'create_studio', methods: [Request::METHOD_POST])]
    #[OA\Post(
        path: '/studios',
        requestBody: new OA\RequestBody(
            description: 'Studio data',
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'street', 'city', 'zipCode', 'country', 'email'],
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'street', type: 'string'),
                    new OA\Property(property: 'city', type: 'string'),
                    new OA\Property(property: 'zipCode', type: 'string'),
                    new OA\Property(property: 'country', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                ],
                type: 'object'
            )
        ),
        tags: ['Studio'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Studio created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Studio already exists',
            ),
        ]
    )]
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
    #[OA\Get(
        path: '/studios',
        tags: ['Studio'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of studios',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'street', type: 'string'),
                            new OA\Property(property: 'city', type: 'string'),
                            new OA\Property(property: 'zipCode', type: 'string'),
                            new OA\Property(property: 'country', type: 'string'),
                            new OA\Property(property: 'email', type: 'string', format: 'email'),
                        ],
                        type: 'object'
                    )
                )
            ),
        ]
    )]
    public function getStudios(): Response
    {
        $studios = $this->getStudiosPort->getStudios();

        return new JsonResponse($studios, Response::HTTP_OK);
    }
}
