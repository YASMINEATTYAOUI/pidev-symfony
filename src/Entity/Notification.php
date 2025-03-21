<?php

namespace App\Entity;

use App\Repository\NotificationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
#[ORM\Table(name: "notifications")]
class Notifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    private ?string $message = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $seen = null;

    #[ORM\ManyToOne(targetEntity: Report::class)]
    #[ORM\JoinColumn(name: "report_id", referencedColumnName: "id")]
    private ?Report $report = null;

    #[ORM\ManyToOne(targetEntity: Events::class)]
    #[ORM\JoinColumn(name: "event_id", referencedColumnName: "id")]
    private ?Events $event = null;

    // Getters and setters...

    public function getId(): ?string
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(?bool $seen): static
    {
        $this->seen = $seen;

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): static
    {
        $this->report = $report;

        return $this;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): static
    {
        $this->event = $event;

        return $this;
    }
}
