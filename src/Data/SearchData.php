<?php

namespace App\Data;

use App\Entity\Campus;
use Doctrine\ORM\Mapping as ORM;



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
     * @ORM\Column(type="datetime")
     */
    public $dateMax;

    /**
     * @ORM\Column(type="datetime")
     */
    public $dateMin;



}