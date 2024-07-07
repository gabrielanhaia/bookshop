<?php

declare(strict_types=1);

namespace App\Application\Port\Input\RegisterNewStudio;

use Symfony\Component\Uid\Uuid;

class StudioDTO
{
    public function __construct(
        public ?Uuid            $id,
        private readonly string $name,
        private readonly string $street,
        private readonly string $city,
        private readonly string $zipCode,
        private readonly string $country,
        private readonly string $email,
    )
    {
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}