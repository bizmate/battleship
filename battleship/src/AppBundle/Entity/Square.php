<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/10/15
 * Time: 21:40
 */

namespace AppBundle\Entity;

class Square {

    const NO_SHOT = '.';
    const MISS = '_';
    const HIT = 'x';

    /**
     * stores the instance status value
     * @var string
     */
    private $status;

    /**
     * This could be a more elaborate reference but currently displayed as an integer for pragmatism within
     * the timeframe
     *
     * @var int

    private $id;*/

    public function __construct( $status = null)
    {
        //$this->id = $id;

        if($status){
            $this->status = $status;
        }
        else{
            $this->status = self::NO_SHOT;
        }
    }

    /**
     * @return string - rapresention of current status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Setter for status - ideally could throw an exception with a status not compatible with the objecj
     * @param $status
     * @return bool
     */
    public function setStatus($status)
    {
        if( $status == self::HIT || $status == self::MISS || $status == self::NO_SHOT)
        {
            return $this->status = $status;
        }
        return false; //maybe an exception
    }
}