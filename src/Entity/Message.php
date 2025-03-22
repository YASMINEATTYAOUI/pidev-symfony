<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: "message")]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "sender_id", referencedColumnName: "id")]
    private ?Citizen $sender = null;

    #[ORM\ManyToOne(targetEntity: Citizen::class)]
    #[ORM\JoinColumn(name: "receiver_id", referencedColumnName: "id")]
    private ?Citizen $receiver = null;

    #[ORM\Column(type: "text")]
    private ?string $content = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $timestamp = null;

    // Getters and setters...
}
