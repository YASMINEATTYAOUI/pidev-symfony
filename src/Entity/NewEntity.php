<?php

namespace App\Entity;

use App\Repository\NewEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewEntityRepository::class)]
class NewEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstThin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstThin(): ?string
    {
        return $this->firstThin;
    }

    public function setFirstThin(string $firstThin): static
    {
        $this->firstThin = $firstThin;

        return $this;
    }
}
