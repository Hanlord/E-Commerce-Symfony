<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $rating = null;

    #[ORM\Column(length: 1000)]
    private ?string $message = null;

    #[ORM\ManyToOne]
    private ?user $fk_user = null;

    #[ORM\ManyToOne]
    private ?product $fk_product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getFkProduct(): ?product
    {
        return $this->fk_product;
    }

    public function setFkProduct(?product $fk_product): self
    {
        $this->fk_product = $fk_product;

        return $this;
    }
}
