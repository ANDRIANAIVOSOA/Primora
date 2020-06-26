<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Helpers\StringHelpers;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataCapteurParJourRepository")
 */
class DataCapteurParJour
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendingDateTime;

    /**
     * @ORM\Column(type="float")
     */
    private $level;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Device")
     * @ORM\JoinColumn(nullable=false, name="device_id", referencedColumnName="id")
     */
    private $deviceId;

    /**
     * @ORM\Column(type="integer")
     */
    private $siteId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $freshData;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Capteur")
     * @ORM\JoinColumn(nullable=false, name="capteur", referencedColumnName="id")
     */
    private $capteur;

    public function __construct()
    {
        $stringHelpers = new StringHelpers();
        $this->id  = $stringHelpers->generateUuid();
        $this->isDeleted = 0;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getSendingDateTime(): ?\DateTimeInterface
    {
        return $this->sendingDateTime;
    }

    public function setSendingDateTime(\DateTimeInterface $sendingDateTime): self
    {
        $this->sendingDateTime = $sendingDateTime;

        return $this;
    }

    public function getLevel(): ?float
    {
        return $this->level;
    }

    public function setLevel(float $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getCapteur(): ?Capteur
    {
        return $this->capteur;
    }

    public function setCapteur(?Capteur $capteur): self
    {
        $this->capteur = $capteur;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getDeviceId(): ?Device
    {
        return $this->deviceId;
    }

    public function setDeviceId(?Device $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    public function setSiteId(int $siteId): self
    {
        $this->siteId = $siteId;

        return $this;
    }

    public function getFreshData(): ?\DateTimeInterface
    {
        return $this->freshData;
    }

    public function setFreshData(\DateTimeInterface $freshData): self
    {
        $this->freshData = $freshData;

        return $this;
    }
}
