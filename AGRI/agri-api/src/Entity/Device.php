<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Helpers\StringHelpers;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site")
     * @ORM\JoinColumn(nullable=true, name="site", referencedColumnName="id", onDelete="SET NULL")
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agentName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessCode")
     * @ORM\JoinColumn(nullable=true, name="access_code", referencedColumnName="id", onDelete="SET NULL")
     */
    private $accessCode;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAgentName(): ?string
    {
        return $this->agentName;
    }

    public function setAgentName(?string $agentName): self
    {
        $this->agentName = $agentName;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

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
