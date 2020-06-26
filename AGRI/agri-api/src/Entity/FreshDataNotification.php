<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FreshDataNotificationRepository")
 */
class FreshDataNotification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $freshData;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\Column(name="user", nullable=true)
     */
    private $userId;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
