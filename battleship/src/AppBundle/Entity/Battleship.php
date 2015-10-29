<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/10/15
 * Time: 23:48
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Ship;

class Battleship extends Ship{

    public function __construct(  $vertical = false )
    {
        //dirty hack to avoid placing this logic in the right place
        //i.e. on a Grid properties aware service (X and Y size)
        $size = 5;



        parent::__construct($size ,  $vertical );
    }
}