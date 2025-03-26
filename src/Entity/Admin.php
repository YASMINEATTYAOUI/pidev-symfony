<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminRepository;


#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: "admin")]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\OneToOne(inversedBy: "admin", targetEntity: User::class, cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;



    
    // Getter for id
    public function getId(): ?int
    {
        return $this->id;
    }
}
