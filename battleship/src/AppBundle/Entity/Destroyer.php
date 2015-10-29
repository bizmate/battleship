<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/10/15
 * Time: 23:48
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Ship;

class Destroyer extends Ship{

    public function __construct($vertical = false)
    {
        parent::__construct(4,  $vertical );
    }
}