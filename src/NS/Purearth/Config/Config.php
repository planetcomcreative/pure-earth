<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 09/02/17
 * Time: 11:07 AM
 */

namespace NS\Purearth\Config;


use NS\Purearth\Common\TimestampableInterface;
use NS\Purearth\Common\TimestampableTrait;

class Config implements TimestampableInterface
{
    use TimestampableTrait;
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = strtolower(preg_replace("/[^A-Za-z0-9]/", '_', $name));
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->getName();
    }
}