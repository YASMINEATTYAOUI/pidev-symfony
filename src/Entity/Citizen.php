<?php

namespace App\Entity;

use App\Repository\CitizenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitizenRepository::class)]
#[ORM\Table(name: "citizen")]
class Citizen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(type: "string", length: 150, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "integer")]
    private ?int $cin = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $score = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $level = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $lastModifiedDate = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $activeStatus = null;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: "address_id", referencedColumnName: "id")]
    private ?Address $address = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $imagePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;
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

    public function getActiveStatus(): ?bool
    {
        return $this->activeStatus;
    }

    public function setActiveStatus(?bool $activeStatus): self
    {
        $this->activeStatus = $activeStatus;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function isActiveStatus(): ?bool
    {
        return $this->activeStatus;
    }
}
