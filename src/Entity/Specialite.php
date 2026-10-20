<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $specialisation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDelivrance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'mes_specialites')]
    private Collection $medecins;

    public function __construct()
    {
        $this->medecins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialisation(): ?string
    {
        return $this->specialisation;
    }

    public function setSpecialisation(string $specialisation): static
    {
        $this->specialisation = $specialisation;

        return $this;
    }

    public function getDateDelivrance(): ?\DateTime
    {
        return $this->dateDelivrance;
    }

    public function setDateDelivrance(\DateTime $dateDelivrance): static
    {
        $this->dateDelivrance = $dateDelivrance;

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
            $medecin->addMesSpecialite($this);
        }

        return $this;
    }

    public function removeMedecin(User $medecin): static
    {
        if ($this->medecins->removeElement($medecin)) {
            $medecin->removeMesSpecialite($this);
        }

        return $this;
    }
}
