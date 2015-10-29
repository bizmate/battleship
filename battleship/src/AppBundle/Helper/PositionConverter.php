<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 29/10/15
 * Time: 21:39
 */

namespace AppBundle\Helper;

class PositionConverter {

    private $letterRange;
    public function __construct( $status = null)
    {
        $this->letterRange = range('A', 'J');
    }

    /**
     *
     * @param $gridRef
     * @return int
     */
    public function gridToPos($gridRef)
    {
        return (int) ($this->letterToNumber($gridRef[0]) . '' . $this->numberToPosIndex($gridRef[1]) );
    }

    /**
     * Converts Grid row letter reference to grid position value
     * @param $letter
     * @return mixed
     */
    private function letterToNumber($letter)
    {
        return array_search($letter, $this->letterRange);
    }

    /**
     * Converts position number part to indexed reference, i.e 4 is index position 3
     * @param $gridNumber
     * @return int
     */
    private function numberToPosIndex($gridNumber)
    {
        if($gridNumber == 0)
            return 9;

        return (int) ($gridNumber - 1);
    }

}