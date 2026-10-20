<?php

namespace App\Entity;

use App\Repository\ObservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObservationRepository::class)]
class Observation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $matricule = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $observation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'observations')]
    private ?User $mes_observation = null;

    #[ORM\ManyToOne(inversedBy: 'observations')]
    private ?DossierPatient $patient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    public function setMatricule(int $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMesObservation(): ?User
    {
        return $this->mes_observation;
    }

    public function setMesObservation(?User $mes_observation): static
    {
        $this->mes_observation = $mes_observation;

        return $this;
    }

    public function getPatient(): ?DossierPatient
    {
        return $this->patient;
    }

    public function setPatient(?DossierPatient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }
}
