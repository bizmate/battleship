<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/10/15
 * Time: 21:37
 */

namespace AppBundle\Entity;

use AppBundle\Exceptions\NoShotSquareException;
use AppBundle\Helper\PositionConverter;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * Class Grid
 * This is the BattleShip game grid
 *
 * @package AppBundle\Entity
 */
class Grid {

    const X = 10;
    const Y = 10;
    const SQUARES_CACHE_REF = 'gridSquares';

    /**
     * Linear representation of the Grid
     * @var Square[]
     */
    private $squares;

    /**
     * @var Battleship
     */
    private $battleShip;

    /**
     * @var Destroyer
     */
    private $destroyer;

    /**
     * @var int
     */
    private $hitsCount;


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

        $this->initShips();
        $this->initCount();

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
        $this->initShips();

        $this->cache->delete('hitsCount');

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

    public function getHitsCount()
    {
        return $this->hitsCount;
    }

    /**
     * Check if game is completed
     * @return bool
     */
    public function isCompleted()
    {
        $shipsSquares = $this->getShipsSquaresPositions();
        foreach($shipsSquares as $shipsSquareInt)
        {
            if($this->squares[$shipsSquareInt]->getStatus() != Square::HIT)
                return false;
        }
        return true;
    }

    /**
     * Hit attempt on Grid
     * @param $gridPos
     * @return string
     * @throws NoShotSquareException
     */
    public function hit($gridPos)
    {
        $this->increaseHitCount();

        $hitPos = $this->positionHelper->gridToPos($gridPos);

        $hitSquare = $this->squares[$hitPos];
        if( $hitSquare->getStatus() == Square::NO_SHOT)
        {
            if(in_array($hitPos , $this->getShipsSquaresPositions()))
            {
                $result = Square::HIT;

            }
            else
            {
                $result = Square::MISS;
            }
            $hitSquare->setStatus($result);
            $this->cache->save(self::SQUARES_CACHE_REF, $this->squares);
            return $result;
        }
        else{
            throw new NoShotSquareException();
        }
    }

    /**
     * Returns an array with all the linear squares positions
     * @return array
     */
    public function getShipsSquaresPositions()
    {
        return array_merge($this->battleShip->getSquaresOccupied(), $this->destroyer->getSquaresOccupied());
    }

    /**
     * Internal function to build the grid
     * a bit too long for my taste
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
    private function initShips()
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

    /**
     * init/build counter
     */
    private function initCount()
    {
        if($this->cache->contains('hitsCount') ){
            $this->hitsCount = $this->cache->fetch('hitsCount');
        }
        else{
            $this->hitsCount = 0;
            $this->cache->save('hitsCount', $this->hitsCount);
        }
    }

    private function increaseHitCount()
    {
        $this->hitsCount++;
        $this->cache->save('hitsCount', $this->hitsCount);
    }

}