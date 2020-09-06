<?php

namespace App\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\CmsSections;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\CmsBlocksRepository")
 */
class CmsBlocks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iconClass;

    /**
     * @ORM\Column(type="integer")
     */
    private $listingOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\Column(type="string", length=120 , nullable=true)
     */
    private $parent;

    /**
     * @var Sections[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="\App\Remolino\CoreBundle\Entity\CmsSections", mappedBy="block", cascade={"persist","remove"})
     * @ORM\OrderBy({"listingOrder" = "ASC"})
     */
    private $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    public function setIconClass(string $iconClass): self
    {
        $this->iconClass = $iconClass;

        return $this;
    }

    /**
     * @return Collection|CmsSections[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(CmsSections $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setBlock($this);
        }

        return $this;
    }

    public function removeSection(CmsSections $section): self
    {
        if ($this->sections->contains($section)) {
            $this->sections->removeElement($section);
            // set the owning side to null (unless already changed)
            if ($section->getBlock() === $this) {
                $section->setBlock(null);
            }
        }

        return $this;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(?string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
