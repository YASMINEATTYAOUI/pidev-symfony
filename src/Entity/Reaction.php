<?php

namespace App\Entity;

use App\Repository\ReactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReactRepository::class)]
#[ORM\Table(name: "react")]
class React
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $isLiked = null;

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

    public function isLiked(): ?bool
    {
        return $this->isLiked;
    }

    public function setIsLiked(?bool $isLiked): static
    {
        $this->isLiked = $isLiked;

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
