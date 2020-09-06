<?php

namespace App\Remolino\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Remolino\CoreBundle\Entity\Home;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\HomeGalleryRepository")
 * @Vich\Uploadable
 */
class HomeGallery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text",nullable=true )
     */
    private $text;

    /**
     * @var Home
     * @ORM\ManyToOne(targetEntity="\App\Remolino\CoreBundle\Entity\Home", inversedBy="homeGalleries")
     */
    private $home;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainImage;

    /**
     * @Assert\File(
     *      mimeTypesMessage = "Please upload a valid format (jpg/.png)",
     *      maxSize = "30720k"
     * )
     * @Vich\UploadableField(mapping="home_gallery", fileNameProperty="mainImage", size="mainImageSize")
     * @var File
     */
    private $mainImageFile;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coverImage;

    /**
     * @Assert\File(
     *      mimeTypes = {"image/jpeg", "image/x-citrix-jpeg", "image/png", "image/x-png", "image/x-citrix-png"},
     *      mimeTypesMessage = "Please upload a valid format (jpg/.png)",
     *      maxSize = "2048k"
     * )
     * @Assert\Image(
     *      minWidth = 800,
     *      minHeight = 1000
     * )
     * @Vich\UploadableField(mapping="homeGallery", fileNameProperty="coverImage", size="coverImageSize")
     * @var File
     */
    private $coverImageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $seoURL;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private $seoDESC;

    /**
     * @ORM\Column(type="string", length=255 ,nullable=true)
     */
    private $seoTITLE;

    /**
     * @ORM\Column(type="smallint" )
     */
    private $listingOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHome(): ?Home
    {
        return $this->home;
    }

    public function setHome(?Home $home): self
    {
        $this->home = $home;

        return $this;
    }

    /**
     * MAIN IMAGE
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $mainImage
     */
    public function setMainImageFile(?File $mainImage = null): void
    {
        $this->mainImageFile = $mainImage;
    }

    public function getMainImageFile(): ?File
    {
        return $this->mainImageFile;
    }

    public function setMainImage(?string $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImageSize(?int $mainImageSize): void
    {
        $this->mainImageSize = $mainImageSize;
    }

    public function getMainImageSize(): ?int
    {
        return $this->mainImageSize;
    }
    public function getListingOrder(): ?int
    {
        return $this->listingOrder;
    }

    public function setListingOrder(int $listingOrder): self
    {
        $this->listingOrder = $listingOrder;

        return $this;
    }

    /**
     * COVER IMAGE
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $coverImage
    */
    public function setCoverImageFile(?File $coverImage = null): void
    {
        $this->coverImageFile = $coverImage;

        if (null !== $coverImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCoverImageFile(): ?File
    {
        return $this->coverImageFile;
    }

    public function setCoverImage(?string $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImageSize(?int $coverImageSize): void
    {
        $this->coverImageSize = $coverImageSize;
    }

    public function getCoverImageSize(): ?int
    {
        return $this->coverImageSize;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getSeoURL(): ?string
    {
        return $this->seoURL;
    }

    public function setSeoURL(string $seoURL): self
    {
        $this->seoURL = $seoURL;

        return $this;
    }

    public function getSeoDESC(): ?string
    {
        return $this->seoDESC;
    }

    public function setSeoDESC(string $seoDESC): self
    {
        $this->seoDESC = $seoDESC;

        return $this;
    }

    public function getSeoTITLE(): ?string
    {
        return $this->seoTITLE;
    }

    public function setSeoTITLE(string $seoTITLE): self
    {
        $this->seoTITLE = $seoTITLE;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
