<?php

namespace App\Entity;

use App\Repository\QuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestRepository::class)]
#[ORM\Table(name: "quest")]
class Quest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "string")]
    private ?string $status = null;

    #[ORM\Column(type: "date")]
    private ?\DateTime $date = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $lastModifiedDate = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id")]
    private ?Citizen $citizen = null;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: "location_id", referencedColumnName: "id")]
    private ?Address $location = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $image = null;

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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(?Address $location): static
    {
        $this->location = $location;

        return $this;
    }
}
