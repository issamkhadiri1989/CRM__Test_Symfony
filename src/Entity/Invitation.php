<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\InvitationStatusEnums;
use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: InvitationRepository::class)]
class Invitation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'invitation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Valid]
    private ?User $employee = null;

    #[ORM\Column(length: 255, enumType: InvitationStatusEnums::class)]
    private ?InvitationStatusEnums $status = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'invitations')]
    private ?Company $company = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $sentAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(User $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getStatus(): ?InvitationStatusEnums
    {
        return $this->status;
    }

    public function setStatus(InvitationStatusEnums $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function isConfirmed(): bool
    {
        return InvitationStatusEnums::CONFIRMED === $this->getStatus();
    }
}
