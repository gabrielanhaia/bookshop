<?php

namespace App\Application\Port\Input\RegisterNewStudio;

use App\Application\Exception\StudioAlreadyExistsException;

interface RegisterNewStudioPort
{
    /**
     * @throws StudioAlreadyExistsException if studio already exists
     */
    public function registerNewStudio(StudioDTO $studioDTO): StudioDTO;
}