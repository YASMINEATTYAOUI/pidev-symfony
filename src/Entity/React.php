<?php

namespace App\Entity;

use App\Repository\ReactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReactRepository::class)]
#[ORM\Table(name: "react")]
#[ORM\UniqueConstraint(name: "unique_post_citizen_like", columns: ["post_id", "citizen_id"])]
class React{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: "reactions")]
    #[ORM\JoinColumn(name: "post_id", referencedColumnName: "id", nullable: false)]
    private ?Post $post = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id", nullable: false)]
    private ?Citizen $citizen = null;

    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    // Getters and Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface {
        return $this->createdAt;
    }

    public function getPost(): ?Post {
        return $this->post;
    }

    public function setPost(?Post $post): static {
        $this->post = $post;
        return $this;
    }

    public function getCitizen(): ?Citizen {
        return $this->citizen;
    }

    public function setCitizen(?Citizen $citizen): static {
        $this->citizen = $citizen;
        return $this;
    }
}