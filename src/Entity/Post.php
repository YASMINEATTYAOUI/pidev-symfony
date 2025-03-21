<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: "post")]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $fileUrl = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $tags = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $lastModifiedDate = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id")]
    private ?Citizen $citizen = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function setFileUrl(?string $fileUrl): static
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): static
    {
        $this->tags = $tags;

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
