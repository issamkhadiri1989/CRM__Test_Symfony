<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\HistoryTypeEnum;
use App\Repository\HistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $historyDatetime = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(length: 255, enumType: HistoryTypeEnum::class)]
    private ?HistoryTypeEnum $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHistoryDatetime(): ?\DateTimeImmutable
    {
        return $this->historyDatetime;
    }

    public function setHistoryDatetime(\DateTimeImmutable $historyDatetime): static
    {
        $this->historyDatetime = $historyDatetime;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getType(): ?HistoryTypeEnum
    {
        return $this->type;
    }

    public function setType(HistoryTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }
}
