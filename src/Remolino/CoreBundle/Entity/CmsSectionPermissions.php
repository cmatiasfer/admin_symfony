<?php

namespace App\Remolino\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \App\Remolino\CoreBundle\Entity\CmsSections;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\CmsSectionPermissionsRepository")
 */
class CmsSectionPermissions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Remolino\CoreBundle\Entity\CmsSections")
     * @ORM\JoinColumn(name="id_section", referencedColumnName="id", nullable=false)
     */
    private $idSection;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reading;

    /**
     * @ORM\Column(type="boolean")
     */
    private $writing;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSection(): ?CmsSections
    {
        return $this->idSection;
    }

    public function setIdSection(?CmsSections $idSection): self
    {
        $this->idSection = $idSection;

        return $this;
    }

    public function getReading(): ?bool
    {
        return $this->reading;
    }

    public function setReading(bool $reading): self
    {
        $this->reading = $reading;

        return $this;
    }

    public function getWriting(): ?bool
    {
        return $this->writing;
    }

    public function setWriting(bool $writing): self
    {
        $this->writing = $writing;

        return $this;
    }

    public function getDeleting(): ?bool
    {
        return $this->deleting;
    }

    public function setDeleting(bool $deleting): self
    {
        $this->deleting = $deleting;

        return $this;
    }
}
