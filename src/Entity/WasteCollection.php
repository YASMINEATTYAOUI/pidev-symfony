<?php

namespace App\Entity;

use App\Repository\WasteCollectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WasteCollectionRepository::class)]
#[ORM\Table(name: "wastecollection")]
class WasteCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $dateTime = null;

    #[ORM\ManyToOne(targetEntity: Address::class)]
    #[ORM\JoinColumn(name: "location_id", referencedColumnName: "id")]
    private ?Address $location = null;

    // Getters and setters...

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

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
