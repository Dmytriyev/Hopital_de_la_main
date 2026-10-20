<?php

namespace App\Entity;

use App\Repository\RdvOperatoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RdvOperatoireRepository::class)]
class RdvOperatoire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateRdvOperatoire = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heurRdvOperatoire = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\ManyToOne(inversedBy: 'rdvOperatoires')]
    private ?Chambre $chambre = null;

    #[ORM\ManyToOne(inversedBy: 'rdvOperatoires')]
    private ?SalleOperatoire $salleOperatoire = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'rdvOperatoires')]
    private Collection $medecins;

    #[ORM\ManyToOne(inversedBy: 'newRdvOperatoire')]
    private ?User $secretaire = null;

    #[ORM\ManyToOne(inversedBy: 'rdvOperatoires')]
    private ?DossierPatient $patient = null;

    public function __construct()
    {
        $this->medecins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRdvOperatoire(): ?\DateTime
    {
        return $this->dateRdvOperatoire;
    }

    public function setDateRdvOperatoire(\DateTime $dateRdvOperatoire): static
    {
        $this->dateRdvOperatoire = $dateRdvOperatoire;

        return $this;
    }

    public function getHeurRdvOperatoire(): ?\DateTime
    {
        return $this->heurRdvOperatoire;
    }

    public function setHeurRdvOperatoire(\DateTime $heurRdvOperatoire): static
    {
        $this->heurRdvOperatoire = $heurRdvOperatoire;

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

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): static
    {
        $this->chambre = $chambre;

        return $this;
    }

    public function getSalleOperatoire(): ?SalleOperatoire
    {
        return $this->salleOperatoire;
    }

    public function setSalleOperatoire(?SalleOperatoire $salleOperatoire): static
    {
        $this->salleOperatoire = $salleOperatoire;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMedecins(): Collection
    {
        return $this->medecins;
    }

    public function addMedecin(User $medecin): static
    {
        if (!$this->medecins->contains($medecin)) {
            $this->medecins->add($medecin);
        }

        return $this;
    }

    public function removeMedecin(User $medecin): static
    {
        $this->medecins->removeElement($medecin);

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
