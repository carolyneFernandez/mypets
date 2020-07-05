<?php

namespace App\Data;

use App\Entity\Veterinaire;
use App\Repository\AnimalRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

class FiltreRdv
{

    /** @var Veterinaire[] */
    public $veterinaires = [];

    /** @var null|DateTime */
    public $dateDebut = null;

    /** @var null|DateTime */
    public $dateFin = null;


}
