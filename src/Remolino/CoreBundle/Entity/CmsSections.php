<?php

namespace App\Remolino\CoreBundle\Entity;


use App\Remolino\CoreBundle\Entity\CmsBlocks;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\CmsSectionsRepository")
 */
class CmsSections
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
     * @ORM\Column(type="string", length=255)
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iconClass;

    /**
     * @ORM\Column(type="text" , nullable = true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255 , nullable = true)
     */
    private $primaryOrder;

    /**
     * @ORM\Column(type="smallint")
     */
    private $listingOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;


    /**
     * @var Block
     * @ORM\ManyToOne(targetEntity="\App\Remolino\CoreBundle\Entity\CmsBlocks", inversedBy="sections")
     * @ORM\OrderBy({"listingOrder" = "DESC"})
     */
    private $block;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getListingOrder(): ?string
    {
        return $this->listingOrder;
    }

    public function setListingOrder(string $listingOrder): self
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

    public function getIdBlock(): ?CmsBlocks
    {
        return $this->idBlock;
    }

    public function setIdBlock(?CmsBlocks $idBlock): self
    {
        $this->idBlock = $idBlock;

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

    public function getPrimaryOrder(): ?string
    {
        return $this->primaryOrder;
    }

    public function setPrimaryOrder(?string $primaryOrder): self
    {
        $this->primaryOrder = $primaryOrder;

        return $this;
    }

    public function getBlock(): ?CmsBlocks
    {
        return $this->block;
    }

    public function setBlock(?CmsBlocks $block): self
    {
        $this->block = $block;

        return $this;
    }

}
