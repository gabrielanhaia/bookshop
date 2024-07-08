<?php

declare(strict_types=1);

namespace App\Application\Port\Output;

use App\Application\Port\Shared\StudioDTOCollection;
use App\Domain\Studio\Model\StudioAggregate;
use Symfony\Component\Uid\Uuid;

interface StudioRepositoryPort
{
    public function saveStudio(StudioAggregate $studio): StudioAggregate;

    public function findStudioByName(string $name): ?StudioAggregate;

    public function findStudioById(Uuid $getStudioId): ?StudioAggregate;

    public function getAllStudios(): StudioDTOCollection;
}
