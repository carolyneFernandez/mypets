<?php

namespace App\Entity;

use App\Repository\SecretaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

    /**
     * Secretaire constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->addRole('ROLE_CLINIQUE');
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
