<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessCode")
     * @ORM\JoinColumn(nullable=true, name="access_code", referencedColumnName="id", onDelete="SET NULL")
     */
    private $accessCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteName(): ?string
    {
        return $this->siteName;
    }

    public function setSiteName(?string $siteName): self
    {
        $this->siteName = $siteName;

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
