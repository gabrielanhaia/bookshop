<?php

namespace App\Domain\Booking\Model;

use App\Domain\Booking\Model\ValueObject\DateRange;
use Symfony\Component\Uid\Uuid;

class BookingAggregate
{
    private function __construct(
        private readonly Uuid      $id,
        private readonly string    $bookingReference,
        private readonly Uuid      $roomId,
        private readonly DateRange $dateRange,
        private readonly Uuid      $userId,
    )
    {
    }

    public static function bookRoom(
        string    $bookingReference,
        Uuid      $roomId,
        DateRange $dateRange,
        Uuid      $userId,
    ): self
    {
        return new self(Uuid::v7(), $bookingReference, $roomId, $dateRange, $userId);
    }

    public static function createWithId(
        Uuid      $id,
        string    $bookingReference,
        Uuid      $roomId,
        DateRange $dateRange,
        Uuid      $userId,
    ): self
    {
        return new self($id, $bookingReference, $roomId, $dateRange, $userId);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBookingReference(): string
    {
        return $this->bookingReference;
    }

    public function getRoomId(): Uuid
    {
        return $this->roomId;
    }

    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }
}