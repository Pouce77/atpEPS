<?php

namespace App\Entity;

use App\Repository\PointsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PointsRepository::class)]
#[UniqueEntity(fields: ['user'], message: 'Il y a déjà un utilisateur pour cet option.')]
class Points
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'points', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $abovePoints = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $underPoints = [];

    #[ORM\Column]
    private ?int $matchLostPoints = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAbovePoints(): array
    {
        return $this->abovePoints;
    }

    public function setAbovePoints(array $abovePoints): static
    {
        $this->abovePoints = $abovePoints;

        return $this;
    }

    public function getUnderPoints(): array
    {
        return $this->underPoints;
    }

    public function setUnderPoints(array $underPoints): static
    {
        $this->underPoints = $underPoints;

        return $this;
    }

    public function getMatchLostPoints(): ?int
    {
        return $this->matchLostPoints;
    }

    public function setMatchLostPoints(int $matchLostPoints): static
    {
        $this->matchLostPoints = $matchLostPoints;

        return $this;
    }
}
