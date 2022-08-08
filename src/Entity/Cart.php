<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\ManyToOne]
    private ?product $fk_product = null;

    #[ORM\ManyToOne]
    private ?user $fk_user = null;

    #[ORM\ManyToOne]
    private ?order $fk_order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getFkProduct(): ?product
    {
        return $this->fk_product;
    }

    public function setFkProduct(?product $fk_product): self
    {
        $this->fk_product = $fk_product;

        return $this;
    }

    public function getFkUser(): ?user
    {
        return $this->fk_user;
    }

    public function setFkUser(?user $fk_user): self
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    public function getFkOrder(): ?order
    {
        return $this->fk_order;
    }

    public function setFkOrder(?order $fk_order): self
    {
        $this->fk_order = $fk_order;

        return $this;
    }
}
