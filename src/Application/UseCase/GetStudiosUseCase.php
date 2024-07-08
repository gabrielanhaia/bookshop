<?php

namespace App\Application\UseCase;

use App\Application\Port\Input\GetStudios\GetStudiosPort;
use App\Application\Port\Output\StudioRepositoryPort;
use App\Application\Port\Shared\StudioDTOCollection;

class GetStudiosUseCase implements GetStudiosPort
{
    public function __construct(private readonly StudioRepositoryPort $studioRepositoryPort)
    {
    }

    public function getStudios(): StudioDTOCollection
    {
        return $this->studioRepositoryPort->getAllStudios();
    }
}