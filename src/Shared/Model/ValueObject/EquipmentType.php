<?php

declare(strict_types=1);

namespace App\Shared\Model\ValueObject;

enum EquipmentType
{
    case SPEAKER;

    case CAMERA;

    case TRIPOD;
}
