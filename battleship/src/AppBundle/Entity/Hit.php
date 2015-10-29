<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 29/10/15
 * Time: 20:02
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Hit {

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern="/^[A-J][0-9]$/",
     *      message="Please use values from A0 to J9",
     * )
     */
    public $position;

}