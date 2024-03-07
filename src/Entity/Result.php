<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbreMatch = null;

    #[ORM\Column]
    private ?int $nbreVictoire = null;

    #[ORM\Column]
    private ?int $nbreDefaite = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNbreVictoire(): ?int
    {
        return $this->nbreVictoire;
    }

    public function setNbreVictoire(int $nbreVictoire): static
    {
        $this->nbreVictoire = $nbreVictoire;

        return $this;
    }

    public function getNbreDefaite(): ?int
    {
        return $this->nbreDefaite;
    }

    public function setNbreDefaite(int $nbreDefaite): static
    {
        $this->nbreDefaite = $nbreDefaite;

        return $this;
    }
}
