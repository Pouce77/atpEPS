<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    private ?int $classement = null;

    #[ORM\Column]
    private ?int $nbreMatch = null;

    #[ORM\Column(length: 255)]
    private ?string $Player = null;

    #[ORM\Column]
    private ?int $arbitre = null;

    #[ORM\Column]
    private ?float $Points = null;

    #[ORM\Column]
    private ?int $goalaverage = null;

    #[ORM\ManyToOne(inversedBy: 'tournaments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClassement(): ?int
    {
        return $this->classement;
    }

    public function setClassement(int $classement): static
    {
        $this->classement = $classement;

        return $this;
    }

    public function getNbreMatch(): ?int
    {
        return $this->nbreMatch;
    }

    public function setNbreMatch(int $nbreMatch): static
    {
        $this->nbreMatch = $nbreMatch;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->Player;
    }

    public function setPlayer(string $Player): static
    {
        $this->Player = $Player;

        return $this;
    }

    public function getArbitre(): ?int
    {
        return $this->arbitre;
    }

    public function setArbitre(int $arbitre): static
    {
        $this->arbitre = $arbitre;

        return $this;
    }

    public function getPoints(): ?float
    {
        return $this->Points;
    }

    public function setPoints(float $Points): static
    {
        $this->Points = $Points;

        return $this;
    }

    public function getGoalaverage(): ?int
    {
        return $this->goalaverage;
    }

    public function setGoalaverage(int $goalaverage): static
    {
        $this->goalaverage = $goalaverage;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
