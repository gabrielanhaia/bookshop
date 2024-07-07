<?php

declare(strict_types=1);

namespace App\Domain\Studio\Model\ValueObject;

enum EquipmentType
{
    case SPEAKER;

    case CAMERA;

    case TRIPOD;
}
