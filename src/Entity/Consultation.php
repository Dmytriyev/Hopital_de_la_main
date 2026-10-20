<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $matricule = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $compteRendu = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?User $mes_consultation = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?DossierPatient $patient = null;

    /**
     * @var Collection<int, Prescription>
     */
    #[ORM\OneToMany(targetEntity: Prescription::class, mappedBy: 'mes_prescriptions')]
    private Collection $prescriptions;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
    }

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

    public function getCompteRendu(): ?string
    {
        return $this->compteRendu;
    }

    public function setCompteRendu(string $compteRendu): static
    {
        $this->compteRendu = $compteRendu;

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

    public function getMesConsultation(): ?User
    {
        return $this->mes_consultation;
    }

    public function setMesConsultation(?User $mes_consultation): static
    {
        $this->mes_consultation = $mes_consultation;

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

    /**
     * @return Collection<int, Prescription>
     */
    public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function addPrescription(Prescription $prescription): static
    {
        if (!$this->prescriptions->contains($prescription)) {
            $this->prescriptions->add($prescription);
            $prescription->setMesPrescriptions($this);
        }

        return $this;
    }

    public function removePrescription(Prescription $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getMesPrescriptions() === $this) {
                $prescription->setMesPrescriptions(null);
            }
        }

        return $this;
    }
}
