<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[ORM\Table(name: "report")]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $lastModifiedDate = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id")]
    private ?Citizen $citizen = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $reportType = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $attachments = null;

    #[ORM\Column(type: "string")]
    private ?string $responseStatus = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $responseText = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $responseDate = null;

    // Getters and setters...

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getLastModifiedDate(): ?\DateTimeInterface
    {
        return $this->lastModifiedDate;
    }

    public function setLastModifiedDate(\DateTimeInterface $lastModifiedDate): static
    {
        $this->lastModifiedDate = $lastModifiedDate;

        return $this;
    }

    public function getReportType(): ?string
    {
        return $this->reportType;
    }

    public function setReportType(?string $reportType): static
    {
        $this->reportType = $reportType;

        return $this;
    }

    public function getAttachments(): ?string
    {
        return $this->attachments;
    }

    public function setAttachments(?string $attachments): static
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function getResponseStatus(): ?string
    {
        return $this->responseStatus;
    }

    public function setResponseStatus(string $responseStatus): static
    {
        $this->responseStatus = $responseStatus;

        return $this;
    }

    public function getResponseText(): ?string
    {
        return $this->responseText;
    }

    public function setResponseText(?string $responseText): static
    {
        $this->responseText = $responseText;

        return $this;
    }

    public function getResponseDate(): ?\DateTimeInterface
    {
        return $this->responseDate;
    }

    public function setResponseDate(\DateTimeInterface $responseDate): static
    {
        $this->responseDate = $responseDate;

        return $this;
    }

    public function getCitizen(): ?Citizen
    {
        return $this->citizen;
    }

    public function setCitizen(?Citizen $citizen): static
    {
        $this->citizen = $citizen;

        return $this;
    }
}
