<?php

namespace App\Entity;

use App\Repository\SecretaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecretaireRepository::class)
 */
class Secretaire extends User
{
    /**
     * @ORM\ManyToOne(targetEntity=Clinique::class, inversedBy="secretaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clinique;

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
