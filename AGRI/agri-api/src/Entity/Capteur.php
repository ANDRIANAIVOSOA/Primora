<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Helpers\StringHelpers;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CapteurRepository")
 * @Vich\Uploadable
 */
class Capteur
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $iconFull;

    /**
     * @ORM\Column(type="text")
     */
    private $iconClear;

    /**
     * @ORM\Column(type="string")
     */
    private $unite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessCode")
     * @ORM\JoinColumn(nullable=true, name="access_code", referencedColumnName="id")
     */
    private $accessCode;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $stringHelpers = new StringHelpers();
        $this->id  = $stringHelpers->generateUuid();
    }

    public function getId(): ?string
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIconFull(): ?string
    {
        return $this->iconFull;
    }

    public function setIconFull(string $iconFull): self
    {
        $this->iconFull = $iconFull;

        return $this;
    }

    public function getIconClear(): ?string
    {
        return $this->iconClear;
    }

    public function setIconClear(string $iconClear): self
    {
        $this->iconClear = $iconClear;

        return $this;
    }

    public function getAccessCode(): ?AccessCode
    {
        return $this->accessCode;
    }

    public function setAccessCode(?AccessCode $accessCode): self
    {
        $this->accessCode = $accessCode;

        return $this;
    }
}
