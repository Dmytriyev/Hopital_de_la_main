<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateNaissance = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $codePostale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(nullable: true)]
    private ?int $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $post = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Specialite>
     */
    #[ORM\ManyToMany(targetEntity: Specialite::class, inversedBy: 'medecins')]
    private Collection $mes_specialites;

    /**
     * @var Collection<int, DossierPatient>
     */
    #[ORM\ManyToMany(targetEntity: DossierPatient::class, inversedBy: 'mes_medecins', cascade: ['persist'])]
    private Collection $mes_patients;

    /**
     * @var Collection<int, Consultation>
     */
    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'mes_consultation')]
    private Collection $consultations;

    /**
     * @var Collection<int, Observation>
     */
    #[ORM\OneToMany(targetEntity: Observation::class, mappedBy: 'mes_observation')]
    private Collection $observations;

    /**
     * @var Collection<int, DossierPatient>
     */
    #[ORM\OneToMany(targetEntity: DossierPatient::class, mappedBy: 'nouveau_patient')]
    private Collection $patient;

    /**
     * @var Collection<int, RdvOperatoire>
     */
    #[ORM\ManyToMany(targetEntity: RdvOperatoire::class, mappedBy: 'medecins')]
    private Collection $rdvOperatoires;

    /**
     * @var Collection<int, RdvOperatoire>
     */
    #[ORM\OneToMany(targetEntity: RdvOperatoire::class, mappedBy: 'secretaire')]
    private Collection $newRdvOperatoire;

    /**
     * @var Collection<int, DossierPatient>
     */
    #[ORM\OneToMany(targetEntity: DossierPatient::class, mappedBy: 'secretaire')]
    private Collection $newPatients;

    /**
     * @var Collection<int, RDV>
     */
    #[ORM\OneToMany(targetEntity: RDV::class, mappedBy: 'secretaire')]
    private Collection $newRdvs;

    /**
     * @var Collection<int, RDV>
     */
    #[ORM\OneToMany(targetEntity: RDV::class, mappedBy: 'medecin')]
    private Collection $mes_rdvs;

    public function __construct()
    {
        $this->mes_specialites = new ArrayCollection();
        $this->mes_patients = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->observations = new ArrayCollection();
        $this->patient = new ArrayCollection();
        $this->rdvOperatoires = new ArrayCollection();
        $this->newRdvOperatoire = new ArrayCollection();
        $this->newPatients = new ArrayCollection();
        $this->newRdvs = new ArrayCollection();
        $this->mes_rdvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        
        return $data;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
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

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(int $codePostale): static
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

    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    public function setMatricule(?int $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(?string $post): static
    {
        $this->post = $post;

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

    /**
     * @return Collection<int, Specialite>
     */
    public function getMesSpecialites(): Collection
    {
        return $this->mes_specialites;
    }

    public function addMesSpecialite(Specialite $mesSpecialite): static
    {
        if (!$this->mes_specialites->contains($mesSpecialite)) {
            $this->mes_specialites->add($mesSpecialite);
        }

        return $this;
    }

    public function removeMesSpecialite(Specialite $mesSpecialite): static
    {
        $this->mes_specialites->removeElement($mesSpecialite);

        return $this;
    }

    /**
     * @return Collection<int, DossierPatient>
     */
    public function getMesPatients(): Collection
    {
        return $this->mes_patients;
    }

    public function addMesPatient(DossierPatient $mesPatient): static
    {
        if (!$this->mes_patients->contains($mesPatient)) {
            $this->mes_patients->add($mesPatient);
        }

        return $this;
    }

    public function removeMesPatient(DossierPatient $mesPatient): static
    {
        $this->mes_patients->removeElement($mesPatient);

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
            $consultation->setMesConsultation($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getMesConsultation() === $this) {
                $consultation->setMesConsultation(null);
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
            $observation->setMesObservation($this);
        }

        return $this;
    }

    public function removeObservation(Observation $observation): static
    {
        if ($this->observations->removeElement($observation)) {
            // set the owning side to null (unless already changed)
            if ($observation->getMesObservation() === $this) {
                $observation->setMesObservation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DossierPatient>
     */
    public function getPatient(): Collection
    {
        return $this->patient;
    }

    public function addPatient(DossierPatient $patient): static
    {
        if (!$this->patient->contains($patient)) {
            $this->patient->add($patient);
            $patient->setNouveauPatient($this);
        }

        return $this;
    }

    public function removePatient(DossierPatient $patient): static
    {
        if ($this->patient->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getNouveauPatient() === $this) {
                $patient->setNouveauPatient(null);
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
            $rdvOperatoire->addMedecin($this);
        }

        return $this;
    }

    public function removeRdvOperatoire(RdvOperatoire $rdvOperatoire): static
    {
        if ($this->rdvOperatoires->removeElement($rdvOperatoire)) {
            $rdvOperatoire->removeMedecin($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RdvOperatoire>
     */
    public function getNewRdvOperatoire(): Collection
    {
        return $this->newRdvOperatoire;
    }

    public function addNewRdvOperatoire(RdvOperatoire $newRdvOperatoire): static
    {
        if (!$this->newRdvOperatoire->contains($newRdvOperatoire)) {
            $this->newRdvOperatoire->add($newRdvOperatoire);
            $newRdvOperatoire->setSecretaire($this);
        }

        return $this;
    }

    public function removeNewRdvOperatoire(RdvOperatoire $newRdvOperatoire): static
    {
        if ($this->newRdvOperatoire->removeElement($newRdvOperatoire)) {
            // set the owning side to null (unless already changed)
            if ($newRdvOperatoire->getSecretaire() === $this) {
                $newRdvOperatoire->setSecretaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DossierPatient>
     */
    public function getNewPatients(): Collection
    {
        return $this->newPatients;
    }

    public function addNewPatient(DossierPatient $newPatient): static
    {
        if (!$this->newPatients->contains($newPatient)) {
            $this->newPatients->add($newPatient);
            $newPatient->setSecretaire($this);
        }

        return $this;
    }

    public function removeNewPatient(DossierPatient $newPatient): static
    {
        if ($this->newPatients->removeElement($newPatient)) {
            // set the owning side to null (unless already changed)
            if ($newPatient->getSecretaire() === $this) {
                $newPatient->setSecretaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RDV>
     */
    public function getNewRdvs(): Collection
    {
        return $this->newRdvs;
    }

    public function addNewRdv(RDV $newRdv): static
    {
        if (!$this->newRdvs->contains($newRdv)) {
            $this->newRdvs->add($newRdv);
            $newRdv->setSecretaire($this);
        }

        return $this;
    }

    public function removeNewRdv(RDV $newRdv): static
    {
        if ($this->newRdvs->removeElement($newRdv)) {
            // set the owning side to null (unless already changed)
            if ($newRdv->getSecretaire() === $this) {
                $newRdv->setSecretaire(null);
            }
        }

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
            $mesRdv->setMedecin($this);
        }

        return $this;
    }

    public function removeMesRdv(RDV $mesRdv): static
    {
        if ($this->mes_rdvs->removeElement($mesRdv)) {
            // set the owning side to null (unless already changed)
            if ($mesRdv->getMedecin() === $this) {
                $mesRdv->setMedecin(null);
            }
        }

        return $this;
    }

}
