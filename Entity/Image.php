<?php

namespace blackknight467\S3ImageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="blackknight467\S3ImageBundle\Repository\ImageRepository")
 * @ValidGarment()
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * This is for the form upload, but is not persisted to the DB
     * 
     * @Assert\File(maxSize="6000000")
     */
    private $uploadedFile;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer")
     */
    private $width = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height = 0;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get uploaded file
     *
     * @return File
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Set uploaded file
     *
     * @param File $file
     */
    public function setUploadedFile($file)
    {
        $this->uploadedFile = $file;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return Image
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get the source path (with name) of the image
     *
     * @return string
     */
    public function getSource()
    {
        return $this->path . $this->name;
    }
}