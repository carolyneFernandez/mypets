<?php

namespace App\Entity;

use App\Repository\RdvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RdvRepository::class)
 */
class Rdv
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Veterinaire::class, inversedBy="rdvs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $veterinaire;

    /**
     * @ORM\ManyToOne(targetEntity=Animal::class, inversedBy="rdvs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $animal;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $domicile = false;

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $urgence = false;

    /**
     * @ORM\OneToOne(targetEntity=Consultation::class, mappedBy="rdv", cascade={"persist", "remove"})
     */
    private $consultation;

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $completed = false;

    /**
     * @ORM\ManyToOne(targetEntity=Proprietaire::class, inversedBy="rdvs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $valide = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Clinique::class, inversedBy="rdvs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $clinique;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVeterinaire(): ?Veterinaire
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?Veterinaire $veterinaire): self
    {
        $this->veterinaire = $veterinaire;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(string $observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    public function getDomicile(): ?bool
    {
        return $this->domicile;
    }

    public function setDomicile(bool $domicile): self
    {
        $this->domicile = $domicile;

        return $this;
    }

    public function getUrgence(): ?bool
    {
        return $this->urgence;
    }

    public function setUrgence(bool $urgence): self
    {
        $this->urgence = $urgence;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        $this->consultation = $consultation;

        // set (or unset) the owning side of the relation if necessary
        $newRdv = null === $consultation ? null : $this;
        if ($consultation->getRdv() !== $newRdv) {
            $consultation->setRdv($newRdv);
        }

        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getProprietaire(): ?Proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Proprietaire $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(?bool $valide): self
    {
        $this->valide = $valide;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

}
