<?php

declare(strict_types=1);

namespace App\Application\Port\Input\GetStudios;

use App\Application\Port\Shared\StudioDTOCollection;

interface GetStudiosPort
{
    public function getStudios(): StudioDTOCollection;
}