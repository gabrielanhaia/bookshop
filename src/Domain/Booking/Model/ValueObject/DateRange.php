<?php

declare(strict_types=1);

namespace App\Domain\Booking\Model\ValueObject;

class DateRange
{
    private function __construct(
        private readonly \DateTimeImmutable $startTime,
        private readonly \DateTimeImmutable $endTime
    ) {
        if ($startTime >= $endTime) {
            throw new \InvalidArgumentException('Start time must be before end time.');
        }
    }

    public static function create(\DateTimeImmutable $startTime, \DateTimeImmutable $endTime): self
    {
        return new self($startTime, $endTime);
    }

    public function getStartTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return $this->endTime;
    }
}
