<?php

namespace App\Entity;

use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numeroChambre = null;

    #[ORM\Column(length: 50)]
    private ?string $typeChambre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alimentation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $dureeOccupation = null;

    /**
     * @var Collection<int, RdvOperatoire>
     */
    #[ORM\OneToMany(targetEntity: RdvOperatoire::class, mappedBy: 'chambre')]
    private Collection $rdvOperatoires;

    public function __construct()
    {
        $this->rdvOperatoires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroChambre(): ?int
    {
        return $this->numeroChambre;
    }

    public function setNumeroChambre(int $numeroChambre): static
    {
        $this->numeroChambre = $numeroChambre;

        return $this;
    }

    public function getTypeChambre(): ?string
    {
        return $this->typeChambre;
    }

    public function setTypeChambre(string $typeChambre): static
    {
        $this->typeChambre = $typeChambre;

        return $this;
    }

    public function getAlimentation(): ?string
    {
        return $this->alimentation;
    }

    public function setAlimentation(?string $alimentation): static
    {
        $this->alimentation = $alimentation;

        return $this;
    }

    public function getDureeOccupation(): ?\DateTime
    {
        return $this->dureeOccupation;
    }

    public function setDureeOccupation(\DateTime $dureeOccupation): static
    {
        $this->dureeOccupation = $dureeOccupation;

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
            $rdvOperatoire->setChambre($this);
        }

        return $this;
    }

    public function removeRdvOperatoire(RdvOperatoire $rdvOperatoire): static
    {
        if ($this->rdvOperatoires->removeElement($rdvOperatoire)) {
            // set the owning side to null (unless already changed)
            if ($rdvOperatoire->getChambre() === $this) {
                $rdvOperatoire->setChambre(null);
            }
        }

        return $this;
    }
}
