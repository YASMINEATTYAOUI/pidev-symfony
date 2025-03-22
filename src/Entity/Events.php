<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
#[ORM\Table(name: "events")]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 30)]
    private ?string $title = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "date")]
    private ?\DateTime $date = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $lastModifiedDate = null;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: "location_id", referencedColumnName: "id")]
    private ?Address $location = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id")]
    private ?Citizen $citizen = null;

    #[ORM\Column(type: "string", length: 200)]
    private ?string $imagePath = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getLastModifiedDate(): ?\DateTime
    {
        return $this->lastModifiedDate;
    }

    public function setLastModifiedDate(\DateTime $lastModifiedDate): self
    {
        $this->lastModifiedDate = $lastModifiedDate;
        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(?Address $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getCitizen(): ?Citizen
    {
        return $this->citizen;
    }

    public function setCitizen(?Citizen $citizen): self
    {
        $this->citizen = $citizen;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }
}
