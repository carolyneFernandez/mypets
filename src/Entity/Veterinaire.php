<?php

namespace App\Entity;

use App\Repository\VeterinaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=VeterinaireRepository::class)
 */
class Veterinaire extends User
{
    /**
     * @ORM\Column(type="time")
     */
    private $intervalBetweenRdv;

    /**
     * @ORM\ManyToOne(targetEntity=Clinique::class, inversedBy="veterinaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clinique;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $formations;

    /**
     * @ORM\OneToMany(targetEntity=VeterinaireHoraire::class, mappedBy="veterinaire", orphanRemoval=true)
     */
    private $veterinaireHoraires;

    /**
     * @ORM\OneToMany(targetEntity=Animal::class, mappedBy="veterinaireHabituel")
     */
    private $animals;

    /**
     * @ORM\OneToMany(targetEntity=Vaccin::class, mappedBy="veterinaire")
     */
    private $vaccins;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="veterinaire", orphanRemoval=true)
     */
    private $rdvs;

    /**
     * @ORM\OneToMany(targetEntity=Consultation::class, mappedBy="veterinaire", orphanRemoval=true)
     */
    private $consultations;

    public function __construct()
    {
        parent::__construct();
        $this->veterinaireHoraires = new ArrayCollection();
        $this->animals = new ArrayCollection();
        $this->vaccins = new ArrayCollection();
        $this->rdvs = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->addRole('ROLE_VETERINAIRE');
        $this->intervalBetweenRdv = new \DateTime('00:00');
    }

    public function getIntervalBetweenRdv(): ?\DateTimeInterface
    {
        return $this->intervalBetweenRdv;
    }

    public function setIntervalBetweenRdv(\DateTimeInterface $intervalBetweenRdv): self
    {
        $this->intervalBetweenRdv = $intervalBetweenRdv;

        return $this;
    }

    public function getClinique(): ?Clinique
    {
        return $this->clinique;
    }

    public function setClinique(?Clinique $clinique): self
    {
        $this->clinique = $clinique;

        return $this;
    }

    public function getFormations(): ?string
    {
        return $this->formations;
    }

    public function setFormations(?string $formations): self
    {
        $this->formations = $formations;

        return $this;
    }

    /**
     * @return Collection|VeterinaireHoraire[]
     */
    public function getVeterinaireHoraires(): Collection
    {
        return $this->veterinaireHoraires;
    }

    public function addVeterinaireHoraire(VeterinaireHoraire $veterinaireHoraire): self
    {
        if (!$this->veterinaireHoraires->contains($veterinaireHoraire)) {
            $this->veterinaireHoraires[] = $veterinaireHoraire;
            $veterinaireHoraire->setVeterinaire($this);
        }

        return $this;
    }

    public function removeVeterinaireHoraire(VeterinaireHoraire $veterinaireHoraire): self
    {
        if ($this->veterinaireHoraires->contains($veterinaireHoraire)) {
            $this->veterinaireHoraires->removeElement($veterinaireHoraire);
            // set the owning side to null (unless already changed)
            if ($veterinaireHoraire->getVeterinaire() === $this) {
                $veterinaireHoraire->setVeterinaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Animal[]
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals[] = $animal;
            $animal->setVeterinaireHabituel($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->contains($animal)) {
            $this->animals->removeElement($animal);
            // set the owning side to null (unless already changed)
            if ($animal->getVeterinaireHabituel() === $this) {
                $animal->setVeterinaireHabituel(null);
            }
        }

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
            $vaccin->setVeterinaire($this);
        }

        return $this;
    }

    public function removeVaccin(Vaccin $vaccin): self
    {
        if ($this->vaccins->contains($vaccin)) {
            $this->vaccins->removeElement($vaccin);
            // set the owning side to null (unless already changed)
            if ($vaccin->getVeterinaire() === $this) {
                $vaccin->setVeterinaire(null);
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
            $rdv->setVeterinaire($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): self
    {
        if ($this->rdvs->contains($rdv)) {
            $this->rdvs->removeElement($rdv);
            // set the owning side to null (unless already changed)
            if ($rdv->getVeterinaire() === $this) {
                $rdv->setVeterinaire(null);
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
            $consultation->setVeterinaire($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->contains($consultation)) {
            $this->consultations->removeElement($consultation);
            // set the owning side to null (unless already changed)
            if ($consultation->getVeterinaire() === $this) {
                $consultation->setVeterinaire(null);
            }
        }

        return $this;
    }

}
