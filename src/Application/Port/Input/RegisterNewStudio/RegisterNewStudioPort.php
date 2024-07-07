<?php

namespace App\Application\Port\Input\RegisterNewStudio;

interface RegisterNewStudioPort
{
    public function registerNewStudio(StudioDTO $studioDTO): StudioDTO;
}