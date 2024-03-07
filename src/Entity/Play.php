<?php

namespace App\Entity;

use App\Repository\PlayRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayRepository::class)]
class Play
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $gagnant_name = null;

    #[ORM\Column(length: 255)]
    private ?string $perdant_name = null;

    #[ORM\Column]
    private ?int $score_gagnant = null;

    #[ORM\Column]
    private ?int $score_perdant = null;

    #[ORM\Column(length: 255)]
    private ?string $arbitre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGagnantName(): ?string
    {
        return $this->gagnant_name;
    }

    public function setGagnantName(string $gagnant_name): static
    {
        $this->gagnant_name = $gagnant_name;

        return $this;
    }

    public function getPerdantName(): ?string
    {
        return $this->perdant_name;
    }

    public function setPerdantName(string $perdant_name): static
    {
        $this->perdant_name = $perdant_name;

        return $this;
    }

    public function getScoreGagnant(): ?int
    {
        return $this->score_gagnant;
    }

    public function setScoreGagnant(int $score_gagnant): static
    {
        $this->score_gagnant = $score_gagnant;

        return $this;
    }

    public function getScorePerdant(): ?int
    {
        return $this->score_perdant;
    }

    public function setScorePerdant(int $score_perdant): static
    {
        $this->score_perdant = $score_perdant;

        return $this;
    }

    public function getArbitre(): ?string
    {
        return $this->arbitre;
    }

    public function setArbitre(string $arbitre): static
    {
        $this->arbitre = $arbitre;

        return $this;
    }
}
