<?php

namespace App\Entity;

use App\Repository\EventCitizenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventCitizenRepository::class)]
#[ORM\Table(name: "event_citizen")]
class EventCitizen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Events::class)]
    #[ORM\JoinColumn(name: "event_id", referencedColumnName: "id")]
    private ?Events $event = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id")]
    private ?Citizen $citizen = null;

    // Getters and setters...

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): static
    {
        $this->event = $event;

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
