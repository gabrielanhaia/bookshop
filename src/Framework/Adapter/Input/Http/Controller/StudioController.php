<?php

declare(strict_types=1);

namespace App\Framework\Adapter\Input\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudioController extends AbstractController
{
    #[Route('/studio', name: 'studio', methods: [Request::METHOD_POST])]
    public function handle(): Response
    {
        return new JsonResponse('Studio created', Response::HTTP_CREATED);
    }
}