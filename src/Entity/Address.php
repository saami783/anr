<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Street $street = null;

    #[ORM\OneToOne(inversedBy: 'address', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /* @TODO Redéfiinir le nom de la variable, on peut supposer qu'on accepte les termes suivant (BIS, TER etc..) */
    #[ORM\Column(length: 40)]
    private ?string $number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?Street
    {
        return $this->street;
    }

    public function setStreet(?Street $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function __toString(): string
    {
        return $this->number . " " . $this->street . " " . $this->street->getCity()->getName();
    }
}
