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
     * @ORM\JoinColumn(nullable=false)
     */
    private $veterinaire;

    /**
     * @ORM\ManyToOne(targetEntity=Animal::class, inversedBy="rdvs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $animal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $domicile;

    /**
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private $urgence;

    /**
     * @ORM\OneToOne(targetEntity=Consultation::class, mappedBy="rdv", cascade={"persist", "remove"})
     */
    private $consultation;


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

}
