<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 */
class Animal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AnimalType::class, inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $race;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $puce;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infosPere;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infosMere;

    /**
     * @ORM\ManyToOne(targetEntity=Veterinaire::class, inversedBy="animals")
     */
    private $veterinaireHabituel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $traitements;

    /**
     * @ORM\ManyToOne(targetEntity=Proprietaire::class, inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $decede;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDeces;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $causeDeces;

    /**
     * @ORM\OneToMany(targetEntity=Vaccin::class, mappedBy="animal", orphanRemoval=true)
     */
    private $vaccins;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="animal", orphanRemoval=true)
     */
    private $rdvs;

    /**
     * @ORM\OneToMany(targetEntity=Consultation::class, mappedBy="animal", orphanRemoval=true)
     */
    private $consultations;

    public function __construct()
    {
        $this->vaccins = new ArrayCollection();
        $this->rdvs = new ArrayCollection();
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?AnimalType
    {
        return $this->type;
    }

    public function setType(?AnimalType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getPuce(): ?string
    {
        return $this->puce;
    }

    public function setPuce(?string $puce): self
    {
        $this->puce = $puce;

        return $this;
    }

    public function getInfosPere(): ?string
    {
        return $this->infosPere;
    }

    public function setInfosPere(string $infosPere): self
    {
        $this->infosPere = $infosPere;

        return $this;
    }

    public function getInfosMere(): ?string
    {
        return $this->infosMere;
    }

    public function setInfosMere(?string $infosMere): self
    {
        $this->infosMere = $infosMere;

        return $this;
    }

    public function getVeterinaireHabituel(): ?Veterinaire
    {
        return $this->veterinaireHabituel;
    }

    public function setVeterinaireHabituel(?Veterinaire $veterinaireHabituel): self
    {
        $this->veterinaireHabituel = $veterinaireHabituel;

        return $this;
    }

    public function getTraitements(): ?string
    {
        return $this->traitements;
    }

    public function setTraitements(?string $traitements): self
    {
        $this->traitements = $traitements;

        return $this;
    }

    public function getProprietaire(): ?Proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Proprietaire $Proprietaire): self
    {
        $this->proprietaire = $Proprietaire;

        return $this;
    }

    public function getDecede(): ?bool
    {
        return $this->decede;
    }

    public function setDecede(bool $decede): self
    {
        $this->decede = $decede;

        return $this;
    }

    public function getDateDeces(): ?\DateTimeInterface
    {
        return $this->dateDeces;
    }

    public function setDateDeces(?\DateTimeInterface $dateDeces): self
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getCauseDeces(): ?string
    {
        return $this->causeDeces;
    }

    public function setCauseDeces(?string $causeDeces): self
    {
        $this->causeDeces = $causeDeces;

        return $this;
    }

    /**
     * @return Collection|Vaccin[]
     */
    public function getVaccins(): Collection
    {
        return $this->vaccins;
    }

    public function addVaccin(Vaccin $vaccin): self
    {
        if (!$this->vaccins->contains($vaccin)) {
            $this->vaccins[] = $vaccin;
            $vaccin->setAnimal($this);
        }

        return $this;
    }

    public function removeVaccin(Vaccin $vaccin): self
    {
        if ($this->vaccins->contains($vaccin)) {
            $this->vaccins->removeElement($vaccin);
            // set the owning side to null (unless already changed)
            if ($vaccin->getAnimal() === $this) {
                $vaccin->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rdv[]
     */
    public function getRdvs(): Collection
    {
        return $this->rdvs;
    }

    public function addRdv(Rdv $rdv): self
    {
        if (!$this->rdvs->contains($rdv)) {
            $this->rdvs[] = $rdv;
            $rdv->setAnimal($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): self
    {
        if ($this->rdvs->contains($rdv)) {
            $this->rdvs->removeElement($rdv);
            // set the owning side to null (unless already changed)
            if ($rdv->getAnimal() === $this) {
                $rdv->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Consultation[]
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setAnimal($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->contains($consultation)) {
            $this->consultations->removeElement($consultation);
            // set the owning side to null (unless already changed)
            if ($consultation->getAnimal() === $this) {
                $consultation->setAnimal(null);
            }
        }

        return $this;
    }

    public function getAge()
    {
        $date = $this->getDateNaissance();
        $now = new DateTime();
        $interval = $now->diff($date);

        return $interval->y . ' an' . ($interval->y > 1 ? 's' : '') . ' et ' . $interval->m . ' mois';
    }

}
