<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 02/08/16
 * Time: 3:44 PM
 */

namespace NS\Purearth\Common;

use Gedmo\Mapping\Annotation as Gedmo;


trait SortableTrait
{
    /**
     * @Gedmo\SortablePosition
     * @var integer
     */
    protected $position;

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}