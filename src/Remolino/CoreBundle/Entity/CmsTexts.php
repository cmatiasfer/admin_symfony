<?php

namespace App\Remolino\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\CmsTextsRepository")
 */
class CmsTexts
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
    private $variable;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title_EN;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title_ES;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $text_EN;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $text_ES;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVariable(): ?string
    {
        return $this->variable;
    }

    public function setVariable(string $variable): self
    {
        $this->variable = $variable;

        return $this;
    }

    public function getTitleEN(): ?string
    {
        return $this->title_EN;
    }

    public function setTitleEN(string $title_EN): self
    {
        $this->title_EN = $title_EN;

        return $this;
    }

    public function getTitleES(): ?string
    {
        return $this->title_ES;
    }

    public function setTitleES(string $title_ES): self
    {
        $this->title_ES = $title_ES;

        return $this;
    }

    public function getTextEN(): ?string
    {
        return $this->text_EN;
    }

    public function setTextEN(string $text_EN): self
    {
        $this->text_EN = $text_EN;

        return $this;
    }

    public function getTextES(): ?string
    {
        return $this->text_ES;
    }

    public function setTextES(string $text_ES): self
    {
        $this->text_ES = $text_ES;

        return $this;
    }
}
