<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $tax_amount = null;

    #[ORM\Column(length: 2)]
    private ?string $tax_code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTaxAmount(): ?string
    {
        return $this->tax_amount;
    }

    public function setTaxAmount(string $tax_amount): static
    {
        $this->tax_amount = $tax_amount;

        return $this;
    }

    public function getTaxCode(): ?string
    {
        return $this->tax_code;
    }

    public function setTaxCode(string $tax_code): static
    {
        $this->tax_code = $tax_code;

        return $this;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tax_amount' => $this->tax_amount,
            'tax_code' => $this->tax_code,
        ];
    }
}
