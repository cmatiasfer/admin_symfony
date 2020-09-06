<?php

namespace App\Remolino\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\PageRepository")
 */
class Page
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
     * @ORM\OneToMany(targetEntity="App\Remolino\CoreBundle\Entity\BlockPage", mappedBy="page")
     */
    private $blocks;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }


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

    /**
     * @return Collection|BlockPage[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(BlockPage $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setPage($this);
        }

        return $this;
    }

    public function removeBlock(BlockPage $block): self
    {
        if ($this->blocks->contains($block)) {
            $this->blocks->removeElement($block);
            // set the owning side to null (unless already changed)
            if ($block->getPage() === $this) {
                $block->setPage(null);
            }
        }

        return $this;
    }
}
