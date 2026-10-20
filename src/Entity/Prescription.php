<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $traitement = null;

    #[ORM\Column(length: 255)]
    private ?string $dureeTraitement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'prescriptions')]
    private ?Consultation $mes_prescriptions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(string $traitement): static
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getDureeTraitement(): ?string
    {
        return $this->dureeTraitement;
    }

    public function setDureeTraitement(string $dureeTraitement): static
    {
        $this->dureeTraitement = $dureeTraitement;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

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

    public function getMesPrescriptions(): ?Consultation
    {
        return $this->mes_prescriptions;
    }

    public function setMesPrescriptions(?Consultation $mes_prescriptions): static
    {
        $this->mes_prescriptions = $mes_prescriptions;

        return $this;
    }
}
