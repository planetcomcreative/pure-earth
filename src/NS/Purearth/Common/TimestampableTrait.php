<?php

namespace NS\Purearth\Common;

use \DateTime;

/**
 * Class HasTimestamp
 * @package NS\Purearth\Common
 */
trait TimestampableTrait
{
    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new DateTime();
    }
}