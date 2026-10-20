<?php

namespace App\Entity;

use App\Repository\DossierPatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierPatientRepository::class)]
class DossierPatient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateNaissance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $codePostale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 50)]
    private ?string $numeroSecu = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $nomMedecinTraitant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomConfiance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenomConfiance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephoneConfiance = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column]
    private ?int $numeroDossier = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'mes_patients')]
    private Collection $mes_medecins;

    /**
     * @var Collection<int, Consultation>
     */
    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'patient')]
    private Collection $consultations;

    /**
     * @var Collection<int, Observation>
     */
    #[ORM\OneToMany(targetEntity: Observation::class, mappedBy: 'patient')]
    private Collection $observations;

    #[ORM\ManyToOne(inversedBy: 'patient')]
    private ?User $nouveau_patient = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'image_patient')]
    private Collection $images;

    /**
     * @var Collection<int, RdvOperatoire>
     */
    #[ORM\OneToMany(targetEntity: RdvOperatoire::class, mappedBy: 'patient')]
    private Collection $rdvOperatoires;

    #[ORM\ManyToOne(inversedBy: 'newPatients')]
    private ?User $secretaire = null;

    /**
     * @var Collection<int, RDV>
     */
    #[ORM\OneToMany(targetEntity: RDV::class, mappedBy: 'patient')]
    private Collection $mes_rdvs;

    public function __construct()
    {
        $this->mes_medecins = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->observations = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->rdvOperatoires = new ArrayCollection();
        $this->mes_rdvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTime $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(?int $codePostale): static
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNumeroSecu(): ?string
    {
        return $this->numeroSecu;
    }

    public function setNumeroSecu(string $numeroSecu): static
    {
        $this->numeroSecu = $numeroSecu;

        return $this;
    }

    public function getNomMedecinTraitant(): ?User
    {
        return $this->nomMedecinTraitant;
    }

    public function setNomMedecinTraitant(?User $nomMedecinTraitant): static
    {
        $this->nomMedecinTraitant = $nomMedecinTraitant;

        return $this;
    }

    public function getNomConfiance(): ?string
    {
        return $this->nomConfiance;
    }

    public function setNomConfiance(?string $nomConfiance): static
    {
        $this->nomConfiance = $nomConfiance;

        return $this;
    }

    public function getPrenomConfiance(): ?string
    {
        return $this->prenomConfiance;
    }

    public function setPrenomConfiance(?string $prenomConfiance): static
    {
        $this->prenomConfiance = $prenomConfiance;

        return $this;
    }

    public function getTelephoneConfiance(): ?string
    {
        return $this->telephoneConfiance;
    }

    public function setTelephoneConfiance(?string $telephoneConfiance): static
    {
        $this->telephoneConfiance = $telephoneConfiance;

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

    public function getNumeroDossier(): ?int
    {
        return $this->numeroDossier;
    }

    public function setNumeroDossier(int $numeroDossier): static
    {
        $this->numeroDossier = $numeroDossier;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMesMedecins(): Collection
    {
        return $this->mes_medecins;
    }

    public function addMesMedecin(User $mesMedecin): static
    {
        if (!$this->mes_medecins->contains($mesMedecin)) {
            $this->mes_medecins->add($mesMedecin);
            $mesMedecin->addMesPatient($this);
        }

        return $this;
    }

    public function removeMesMedecin(User $mesMedecin): static
    {
        if ($this->mes_medecins->removeElement($mesMedecin)) {
            $mesMedecin->removeMesPatient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setPatient($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getPatient() === $this) {
                $consultation->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Observation>
     */
    public function getObservations(): Collection
    {
        return $this->observations;
    }

    public function addObservation(Observation $observation): static
    {
        if (!$this->observations->contains($observation)) {
            $this->observations->add($observation);
            $observation->setPatient($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation): static
    {
        if ($this->observations->removeElement($observation)) {
            // set the owning side to null (unless already changed)
            if ($observation->getPatient() === $this) {
                $observation->setPatient(null);
            }
        }

        return $this;
    }

    public function getNouveauPatient(): ?User
    {
        return $this->nouveau_patient;
    }

    public function setNouveauPatient(?User $nouveau_patient): static
    {
        $this->nouveau_patient = $nouveau_patient;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setImagePatient($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getImagePatient() === $this) {
                $image->setImagePatient(null);
            }
        }

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
            $rdvOperatoire->setPatient($this);
        }

        return $this;
    }

    public function removeRdvOperatoire(RdvOperatoire $rdvOperatoire): static
    {
        if ($this->rdvOperatoires->removeElement($rdvOperatoire)) {
            // set the owning side to null (unless already changed)
            if ($rdvOperatoire->getPatient() === $this) {
                $rdvOperatoire->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prescription>
     */
    public function getPrescriptions(): Collection
    {
        $prescriptions = new ArrayCollection();

        foreach ($this->consultations as $consultation) {
            foreach ($consultation->getPrescriptions() as $prescription) {
                $prescriptions->add($prescription);
            }
        }

        return $prescriptions;
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

    /**
     * @return Collection<int, RDV>
     */
    public function getMesRdvs(): Collection
    {
        return $this->mes_rdvs;
    }

    public function addMesRdv(RDV $mesRdv): static
    {
        if (!$this->mes_rdvs->contains($mesRdv)) {
            $this->mes_rdvs->add($mesRdv);
            $mesRdv->setPatient($this);
        }

        return $this;
    }

    public function removeMesRdv(RDV $mesRdv): static
    {
        if ($this->mes_rdvs->removeElement($mesRdv)) {
            // set the owning side to null (unless already changed)
            if ($mesRdv->getPatient() === $this) {
                $mesRdv->setPatient(null);
            }
        }

        return $this;
    }
}
