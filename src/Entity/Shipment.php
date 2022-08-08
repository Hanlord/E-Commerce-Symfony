<?php

namespace App\Entity;

use App\Repository\ShipmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipmentRepository::class)]
class Shipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $company = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $shipDate = null;

    #[ORM\ManyToOne]
    private ?address $fk_address = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getShipDate(): ?\DateTimeInterface
    {
        return $this->shipDate;
    }

    public function setShipDate(\DateTimeInterface $shipDate): self
    {
        $this->shipDate = $shipDate;

        return $this;
    }

    public function getFkAddress(): ?address
    {
        return $this->fk_address;
    }

    public function setFkAddress(?address $fk_address): self
    {
        $this->fk_address = $fk_address;

        return $this;
    }
}
