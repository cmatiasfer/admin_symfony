<?php

namespace App\Remolino\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \App\Remolino\CoreBundle\Entity\Page;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\BlockPageRepository")
 */
class BlockPage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $title;

   /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $seoTITLE;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $seoURL;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $seoDESC;

    /**
     * @ORM\Column(type="smallint" )
     */
    private $listingOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Remolino\CoreBundle\Entity\Page", inversedBy="blocks")
     */
    private $page;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getSeoTITLE(): ?string
    {
        return $this->seoTITLE;
    }

    public function setSeoTITLE(string $seoTITLE): self
    {
        $this->seoTITLE = $seoTITLE;

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

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getHeaderVisible(): ?bool
    {
        return $this->headerVisible;
    }

    public function setHeaderVisible(bool $headerVisible): self
    {
        $this->headerVisible = $headerVisible;

        return $this;
    }

    public function getFooterVisible(): ?bool
    {
        return $this->footerVisible;
    }

    public function setFooterVisible(bool $footerVisible): self
    {
        $this->footerVisible = $footerVisible;

        return $this;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }
}
