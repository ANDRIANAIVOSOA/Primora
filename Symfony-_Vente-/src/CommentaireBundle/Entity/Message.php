<?php

namespace CommentaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="CommentaireBundle\Repository\MessageRepository")
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=255)
     */
    private $message;

     /**   
    * @ORM\ManyToOne(targetEntity="ProprietaireBundle\Entity\Proprietaire")   
    * @ORM\JoinColumn(name="proprietaire_id", referencedColumnName="id")
    */
  private $proprietaire;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set proprietaire
     *
     * @param \ProprietaireBundle\Entity\Proprietaire $proprietaire
     *
     * @return Message
     */
    public function setProprietaire(\ProprietaireBundle\Entity\Proprietaire $proprietaire = null)
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return \ProprietaireBundle\Entity\Proprietaire
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }

    public function __toString() {
        return $this->message();
    }
}
