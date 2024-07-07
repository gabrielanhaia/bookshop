<?php

declare(strict_types=1);

namespace App\Domain\Studio\Model\ValueObject;

class Capacity implements \Stringable
{
    private function __construct(
        private readonly int $value
    ) {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Capacity must be greater than zero.');
        }
    }

    public static function create(int $value): self
    {
        return new self($value);
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(Capacity $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
