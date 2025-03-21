<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: "comment")]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $content = null;

    #[ORM\Column(type: "date")]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTime $lastModifiedDate = null;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(name: "post_id", referencedColumnName: "id")]
    private ?Post $post = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id")]
    private ?Citizen $citizen = null;

    // Getters and setters...

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function setLastModifiedDate(?\DateTimeInterface $lastModifiedDate): static
    {
        $this->lastModifiedDate = $lastModifiedDate;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

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
