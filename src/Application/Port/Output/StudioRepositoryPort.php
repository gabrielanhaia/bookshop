<?php

declare(strict_types=1);

namespace App\Application\Port\Output;

use App\Domain\Studio\Model\StudioAggregate;

interface StudioRepositoryPort
{
    public function saveStudio(StudioAggregate $studio): StudioAggregate;
}
