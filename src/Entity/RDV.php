<?php

namespace App\Entity;

use App\Repository\RDVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RDVRepository::class)]
class RDV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateRdv = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heurRdv = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'newRdvs')]
    private ?User $secretaire = null;

    #[ORM\ManyToOne(inversedBy: 'mes_rdvs')]
    private ?User $medecin = null;

    #[ORM\ManyToOne(inversedBy: 'mes_rdvs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DossierPatient $patient = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRdv(): ?\DateTime
    {
        return $this->dateRdv;
    }

    public function setDateRdv(\DateTime $dateRdv): static
    {
        $this->dateRdv = $dateRdv;

        return $this;
    }

    public function getHeurRdv(): ?\DateTime
    {
        return $this->heurRdv;
    }

    public function setHeurRdv(\DateTime $heurRdv): static
    {
        $this->heurRdv = $heurRdv;

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

    public function getSecretaire(): ?User
    {
        return $this->secretaire;
    }

    public function setSecretaire(?User $secretaire): static
    {
        $this->secretaire = $secretaire;

        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): static
    {
        $this->medecin = $medecin;

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
