<?php

declare(strict_types=1);

namespace App\Domain\Studio\Model\ValueObject;

class Address
{
    public function __construct(
        private readonly string $street,
        private readonly string $city,
        private readonly string $zipCode,
        private readonly string $country
    ) {
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

    public function equals(Address $other): bool
    {
        return $this->street === $other->street
            && $this->city === $other->city
            && $this->zipCode === $other->zipCode
            && $this->country === $other->country;
    }
}