<?php

namespace NS\Purearth\Product;
use NS\Purearth\Common\TimestampableTrait;
use NS\Purearth\Common\TimestampableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class BaseProduct
 * @package NS\Purearth\Product
 * @Vich\Uploadable
 */
abstract class AbstractProduct implements TimestampableInterface
{
    use TimestampableTrait;
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var boolean
     */
    protected $gst;

    /**
     * @var boolean
     */
    protected $pst;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var ArrayCollection
     */
    protected $orderProducts;

    /**
     * @var File
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     */
    protected $imageFile;

    /**
     * @var string
     */
    protected $imageName;

    protected $salePrice;

    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
        $this->price = 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return boolean
     */
    public function hasGst()
    {
        return $this->gst;
    }

    public function isGst()
    {
        return $this->hasGst();
    }

    /**
     * @param boolean $gst
     */
    public function setGst($gst)
    {
        $this->gst = $gst;
    }

    /**
     * @return boolean
     */
    public function hasPst()
    {
        return $this->pst;
    }

    public function isPst()
    {
        return $this->hasPst();
    }


    /**
     * @param boolean $pst
     */
    public function setPst($pst)
    {
        $this->pst = $pst;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'Products';
    }

    /**
     * @return ArrayCollection
     */
    public function getOrderProducts()
    {
        return $this->orderProducts;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ? $this->getName() : '';
    }

    /**
     * @param File|null $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        //Doctrine won't persist if only the file changes
        if ($this->imageFile instanceof File) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    public function isAvailable()
    {
        return true;
    }

    public function isChargable()
    {
        return $this->isAvailable() && $this->getPrice() && $this->getPrice() > 0;
    }

    /**
     * @return mixed
     */
    public function getSalePrice()
    {
        return $this->salePrice ? $this->salePrice : $this->getPrice();
    }

    /**
     * @param mixed $salePrice
     */
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;
    }
}