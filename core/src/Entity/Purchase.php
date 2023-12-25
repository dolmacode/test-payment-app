<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Query\AST\Functions\CurrentTimestampFunction;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $client_name = null;

    #[ORM\Column]
    private ?\DateTime $created_at;

    #[ORM\Column(length: 255)]
    private ?string $tax_code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(?string $client_name): static
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

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
}
