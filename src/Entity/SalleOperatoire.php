<?php

namespace App\Entity;

use App\Repository\SalleOperatoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleOperatoireRepository::class)]
class SalleOperatoire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numeroSalle = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heurOperation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $dureeOperation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    /**
     * @var Collection<int, RdvOperatoire>
     */
    #[ORM\OneToMany(targetEntity: RdvOperatoire::class, mappedBy: 'salleOperatoire')]
    private Collection $rdvOperatoires;

    public function __construct()
    {
        $this->rdvOperatoires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroSalle(): ?int
    {
        return $this->numeroSalle;
    }

    public function setNumeroSalle(int $numeroSalle): static
    {
        $this->numeroSalle = $numeroSalle;

        return $this;
    }

    public function getHeurOperation(): ?\DateTime
    {
        return $this->heurOperation;
    }

    public function setHeurOperation(\DateTime $heurOperation): static
    {
        $this->heurOperation = $heurOperation;

        return $this;
    }

    public function getDureeOperation(): ?\DateTime
    {
        return $this->dureeOperation;
    }

    public function setDureeOperation(\DateTime $dureeOperation): static
    {
        $this->dureeOperation = $dureeOperation;

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

    /**
     * @return Collection<int, RdvOperatoire>
     */
    public function getRdvOperatoires(): Collection
    {
        return $this->rdvOperatoires;
    }

    public function addRdvOperatoire(RdvOperatoire $rdvOperatoire): static
    {
        if (!$this->rdvOperatoires->contains($rdvOperatoire)) {
            $this->rdvOperatoires->add($rdvOperatoire);
            $rdvOperatoire->setSalleOperatoire($this);
        }

        return $this;
    }

    public function removeRdvOperatoire(RdvOperatoire $rdvOperatoire): static
    {
        if ($this->rdvOperatoires->removeElement($rdvOperatoire)) {
            // set the owning side to null (unless already changed)
            if ($rdvOperatoire->getSalleOperatoire() === $this) {
                $rdvOperatoire->setSalleOperatoire(null);
            }
        }

        return $this;
    }
}
