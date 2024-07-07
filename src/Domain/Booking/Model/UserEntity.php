<?php

namespace App\Domain\Booking\Model;

use App\Shared\Model\ValueObject\Email;
use Symfony\Component\Uid\Uuid;

class UserEntity
{
    private function __construct(
        private readonly Uuid  $id,
        private readonly Email $email,
    )
    {
    }

    public static function create(
        Email $email,
    ): self
    {
        return new self(Uuid::v7(), $email);
    }

    public static function createWithId(
        Uuid  $id,
        Email $email,
    ): self
    {
        return new self($id, $email);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }
}