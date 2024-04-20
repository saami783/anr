<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
#[Vich\Uploadable]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: false)]
    private ?bool $isLastest = false;

    #[Vich\UploadableField(mapping: 'avatar', fileNameProperty: 'fileName')]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $upadtedAt = null;

    #[ORM\OneToOne(inversedBy: 'avatar', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isIsLastest(): ?bool
    {
        return $this->isLastest;
    }

    public function setIsLastest(bool $isLastest): static
    {
        $this->isLastest = $isLastest;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->upadtedAt = new \DateTimeImmutable();
        }
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getUpadtedAt(): ?\DateTimeImmutable
    {
        return $this->upadtedAt;
    }

    public function setUpadtedAt(\DateTimeImmutable $upadtedAt): static
    {
        $this->upadtedAt = $upadtedAt;

        return $this;
    }

}
