<?php

namespace App\Data;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;


class SearchData
{


    /**
     * @var string|null
     */
    public $q = '';

    /**
     * @var Campus|null
     */
    public $campus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $dateMax;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $dateMin;

    /**
     * @ORM\Column(type="boolean")
     */
    public $isOrganisateur;


}