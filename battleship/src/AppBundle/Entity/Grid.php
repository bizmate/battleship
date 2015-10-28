<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/10/15
 * Time: 21:37
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Square;

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
     * @var \Doctrine\Common\Cache\FilesystemCache
     */
    private $cache;

    public function __construct( $cache )
    {
        $this->cache = $cache;
        if($this->cache->contains('gridSquares') ){
            // assume what is cached is consistent
            $this->squares = $cache->fetch(self::SQUARES_CACHE_REF);
        }
        else{
            $this->buildGrid();
        }
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
    }

    private function buildGrid()
    {
        $squares = array();
        for($i = 0; $i < (self::X * self::Y); $i++){
            $squares[] = new Square();
        }
        $this->squares = $squares;
        $this->cache->save(self::SQUARES_CACHE_REF, $this->squares);
    }

}