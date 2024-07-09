<?php

declare(strict_types=1);

namespace App\Shared\Model\ValueObject;

enum EquipmentType
{
    case SPEAKER;

    case CAMERA;

    case TRIPOD;

    public static function fromString(string $type): EquipmentType
    {
        return match ($type) {
            'SPEAKER' => EquipmentType::SPEAKER,
            'CAMERA' => EquipmentType::CAMERA,
            'TRIPOD' => EquipmentType::TRIPOD,
            default => throw new \InvalidArgumentException('Invalid equipment type'),
        };
    }
}