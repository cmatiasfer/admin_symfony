<?php

namespace App\Remolino\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Remolino\CoreBundle\Entity\MailTemplateRepository")
 */
class MailTemplate
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
    private $nameTemplate;

    /**
     * @ORM\Column(type="smallint")
     */
    private $typeSend;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $mailTo;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $html;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameTemplate(): ?string
    {
        return $this->nameTemplate;
    }

    public function setNameTemplate(string $nameTemplate): self
    {
        $this->nameTemplate = $nameTemplate;

        return $this;
    }



    public function getMailTo(): ?string
    {
        return $this->mailTo;
    }

    public function setMailTo(string $mailTo): self
    {
        $this->mailTo = $mailTo;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getTypeSend(): ?int
    {
        return $this->typeSend;
    }

    public function setTypeSend(int $typeSend): self
    {
        $this->typeSend = $typeSend;

        return $this;
    }



}