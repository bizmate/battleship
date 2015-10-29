<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/10/15
 * Time: 23:37
 */

namespace AppBundle\Entity;


abstract class Ship {

    /**
     * How many squares/size for this ship?
     * @var int
     */
    private $size;

    /**
     * Set to true if Ship has vertical orientation
     * @var bool
     */
    private $vertical;

    /**
     * first square id where the ship is hooked
     * @var int
     */
    private $hookedAt;

    /**
     * @param $size
     * @param bool $vertical - horizontal by default
     */
    public function __construct( $size , $vertical = false )
    {
        $this->vertical = $vertical;
        $this->size = $size;

        if($vertical)
        {
            $verticalMax = ( ((10 - $size + 1) * 10) - 1 ) ;
            $hookedAt = rand(0 , $verticalMax);
        }
        else{
            // using a fixed size in relation to the hook positioning, logic should be somewhere else
            $hook = rand(0,99);

            if($hook != 0)
            {
                $remainder = $hook % 10;
                $maxRemainder = 10 - $size;
                if($remainder >= $maxRemainder)
                {
                    //shift hook/first quare of Ship to make sure it is withing board/grid
                    $adjust = $maxRemainder + 1 - $remainder ;
                    $hook = $hook - $adjust;
                }
            }

            $hookedAt = $hook;
        }

        $this->hookedAt = $hookedAt;
    }

    /**
     * Returns array with list of squares occupied by Ship
     * @param $xSize
     * @return array
     */
    public function getSquaresOccupied($xSize = 10)
    {
        $squaresOccupied = [$this->hookedAt];
        if($this->vertical){
            for($i = 1; $i < $this->size; $i++)
            {
                $squaresOccupied[] = ($i * $xSize ) + $this->hookedAt;
            }
        }
        else{
            for($i = 1; $i < $this->size; $i++)
            {
                $squaresOccupied[] = $i  + $this->hookedAt;
            }
        }
        return $squaresOccupied;

    }


}