<?php

namespace App\Entity;

use App\Repository\VaccinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VaccinRepository::class)
 */
class Vaccin
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Animal::class, inversedBy="vaccins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $animal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Veterinaire::class, inversedBy="vaccins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $veterinaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * @ORM\ManyToOne(targetEntity=VaccinType::class, inversedBy="vaccins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vaccinType;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVeterinaire(): ?Veterinaire
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?Veterinaire $veterinaire): self
    {
        $this->veterinaire = $veterinaire;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    public function getVaccinType(): ?VaccinType
    {
        return $this->vaccinType;
    }

    public function setVaccinType(?VaccinType $vaccinType): self
    {
        $this->vaccinType = $vaccinType;

        return $this;
    }

}
