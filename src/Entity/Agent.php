<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
#[ORM\Table(name: "agent")]
class Agent
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
    private ?int $phoneNumber = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $activeStatus = null;

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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;
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

    public function isActiveStatus(): ?bool
    {
        return $this->activeStatus;
    }
}
