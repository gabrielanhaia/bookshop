<?php

namespace App\Application\Port\Shared;

use Doctrine\Common\Collections\ArrayCollection;

class StudioDTOCollection extends ArrayCollection implements \JsonSerializable
{
    public function __construct(StudioDTO ...$elements)
    {
        parent::__construct($elements);
    }

    public function add(mixed $element): void
    {
        if (!$element instanceof StudioDTO) {
            throw new \InvalidArgumentException('Only StudioDTO objects are allowed.');
        }

        parent::add($element);
    }

    public function get(int|string $key): StudioDTO
    {
        return parent::get($key);
    }

    public function current(): StudioDTO
    {
        return parent::current();
    }


    public function jsonSerialize(): array
    {
        $result = [];
        /** @var StudioDTO $studioDTO */
        foreach ($this as $studioDTO) {
            $result[] = $studioDTO->jsonSerialize();
        }

        return $result;
    }
}
