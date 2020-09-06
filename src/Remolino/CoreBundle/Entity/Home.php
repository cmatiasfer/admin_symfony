<?php

namespace App\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\HomeGallery;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\HomeRepository")
 */
class Home
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
     * @var HomeImage[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="\App\Remolino\CoreBundle\Entity\HomeGallery", mappedBy="home",cascade={"persist","remove"})
     */
    private $homeGalleries;

    public function __construct()
    {
        $this->homeGalleries = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
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

    /**
     * @return Collection|HomeGallery[]
     */
    public function getHomeGalleries(): Collection
    {
        return $this->homeGalleries;
    }

    public function addHomeGallery(HomeGallery $homeGallery): self
    {
        if (!$this->homeGalleries->contains($homeGallery)) {
            $this->homeGalleries[] = $homeGallery;
            $homeGallery->setHome($this);
        }

        return $this;
    }

    public function removeHomeGallery(HomeGallery $homeGallery): self
    {
        if ($this->homeGalleries->contains($homeGallery)) {
            $this->homeGalleries->removeElement($homeGallery);
            // set the owning side to null (unless already changed)
            if ($homeGallery->getHome() === $this) {
                $homeGallery->setHome(null);
            }
        }
    }
}