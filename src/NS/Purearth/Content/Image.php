<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 16/08/16
 * Time: 12:16 PM
 */

namespace NS\Purearth\Content;

use NS\Purearth\Common\TimestampableInterface;
use NS\Purearth\Common\TimestampableTrait;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class Image
 * @package NS\Purearth\Content
 * @Vich\Uploadable
 */
class Image implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var File
     * @Vich\UploadableField(mapping="global_image", fileNameProperty="imageName")
     */
    protected $imageFile;

    /**
     * @var string
     */
    protected $imageName;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $title;

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
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}