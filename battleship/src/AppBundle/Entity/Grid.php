<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/10/15
 * Time: 21:37
 */

namespace AppBundle\Entity;

use AppBundle\Helper\PositionConverter;
use Doctrine\Common\Cache\FilesystemCache;

class Grid {

    const X = 10;
    const Y = 10;
    const SQUARES_CACHE_REF = 'gridSquares';

    /**
     * Linear representation of the Grid
     * @var Square[]
     */
    private $squares;

    private $battleShip;

    private $destroyer;


    /**
     * @var \Doctrine\Common\Cache\FilesystemCache
     */
    private $cache;

    /**
     * @var \AppBundle\Helper\PositionConverter
     */
    private $positionHelper;

    public function __construct( FilesystemCache $cache , PositionConverter $positionHelper )
    {
        $this->cache = $cache;
        $this->positionHelper = $positionHelper;
        if($this->cache->contains('gridSquares') ){
            // assume what is cached is consistent
            $this->squares = $cache->fetch(self::SQUARES_CACHE_REF);
        }
        else{
            $this->buildGrid();
        }

        $this->setShips();
    }

    /**
     * @return array - returns current representation of the grid/squares
     */
    public function show()
    {
        return array_chunk($this->squares, self::X);
    }

    /**
     * Resets grid
     */
    public function reset()
    {
        if($this->cache->contains('gridSquares') ){
            $this->cache->delete('gridSquares');
        }
        $this->buildGrid();

        $this->cache->delete('ships');
        $this->setShips();
    }



    public function getShips()
    {
        return array('battleShip'=>$this->battleShip , 'destroyer' => $this->destroyer);
    }

    public function getBattleship()
    {
        return $this->battleShip;
    }

    public function getDestroyer()
    {
        return $this->destroyer;
    }

    public function hit($gridPos)
    {
        return $this->positionHelper->gridToPos($gridPos);
    }

    /**
     * Internal function to build the grid
     */
    private function buildGrid()
    {
        $squares = array();
        for($i = 0; $i < (self::X * self::Y); $i++){
            $squares[] = new Square();
        }
        $this->squares = $squares;
        $this->cache->save(self::SQUARES_CACHE_REF, $this->squares);
    }

    /**
     * Internal function build and set the ships
     */
    private function setShips()
    {
        if($this->cache->contains('ships') ){
            $ships = $this->cache->fetch('ships');
            $this->battleShip = $ships['battleShip'];
            $this->destroyer = $ships['destroyer'];
        }
        else{
            $orientation1 = rand(0,1) ;
            $orientation2 = rand(0,1) ;

            $this->battleShip = new Battleship($orientation1);
            $destroyer = new Destroyer($orientation2);

            //ships cannot be in the same square
            while(!empty(array_intersect($this->battleShip->getSquaresOccupied(), $destroyer->getSquaresOccupied())))
            {
                $destroyer = new Destroyer($orientation2);
            }
            $this->destroyer = $destroyer;

            $this->cache->save('ships', array('battleShip'=>$this->battleShip , 'destroyer' => $this->destroyer) );
        }

    }

}