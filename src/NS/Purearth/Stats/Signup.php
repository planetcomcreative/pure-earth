<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 13/03/19
 * Time: 3:34 PM
 */

namespace NS\Purearth\Stats;


use NS\Purearth\Common\TimestampableInterface;
use NS\Purearth\Common\TimestampableTrait;

class Signup implements TimestampableInterface
{
    const TYPE_SIDEBAR = 1,
          TYPE_MODAL = 2,
          TYPE_CHECKOUT = 3;

    use TimestampableTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $wasTriggered;

    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isWasTriggered()
    {
        return $this->wasTriggered;
    }

    /**
     * @param bool $wasTriggered
     */
    public function setWasTriggered($wasTriggered)
    {
        $this->wasTriggered = $wasTriggered;
    }
}
