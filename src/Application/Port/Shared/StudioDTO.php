<?php

declare(strict_types=1);

namespace App\Application\Port\Shared;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class StudioDTO implements \JsonSerializable
{
    private ?Uuid $id = null;

    #[Assert\Country]
    private string $country;

    #[Assert\Email]
    private string $email;

    private function __construct(
        ?Uuid                   $id,
        private readonly string $name,
        private readonly string $street,
        private readonly string $city,
        private readonly string $zipCode,
        string                  $country,
        string                  $email,
    ) {
        $this->id = $id;
        $this->country = $country;
        $this->email = $email;
    }

    public static function create(
        string $name,
        string $street,
        string $city,
        string $zipCode,
        string $country,
        string $email
    ): self {
        return new self(null, $name, $street, $city, $zipCode, $country, $email);
    }

    public static function createWithId(
        Uuid   $id,
        string $name,
        string $street,
        string $city,
        string $zipCode,
        string $country,
        string $email
    ): self {
        return new self($id, $name, $street, $city, $zipCode, $country, $email);
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'street' => $this->street,
            'city' => $this->city,
            'zipCode' => $this->zipCode,
            'country' => $this->country,
            'email' => $this->email
        ];
    }
}
