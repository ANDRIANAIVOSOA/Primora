<?php

namespace ProprietaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ChaussureBundle\Entity\Chaussure;

/**
 * Proprietaire
 *
 * @ORM\Table(name="proprietaire")
 * @ORM\Entity(repositoryClass="ProprietaireBundle\Repository\ProprietaireRepository")
 * @vich\Uploadable
 */
class Proprietaire
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
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="contact", type="integer")
     */
    private $contact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="datetime")
     */
    private $datecreation;

     /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
    
    * @ORM\OneToMany(targetEntity="ChaussureBundle\Entity\Chaussure", mappedBy="proprietaire")    
    *     
    */
    private $chaussure;

     /**
    
    * @ORM\OneToMany(targetEntity="VetementBundle\Entity\Vetement", mappedBy="proprietaire")    
    *     
    */
    private $vetement;

     /**
    
    * @ORM\OneToMany(targetEntity="VoitureBundle\Entity\Voiture", mappedBy="proprietaire")    
    *     
    */
    private $voiture;

     /**
    
    * @ORM\OneToMany(targetEntity="CommentaireBundle\Entity\Message", mappedBy="proprietaire")    
    *     
    */
    private $mess;


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
     * Set image
     *
     * @param string $image
     *
     * @return Proprietaire
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Proprietaire
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set contact
     *
     * @param integer $contact
     *
     * @return Proprietaire
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return int
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     *
     * @return Proprietaire
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;        
    }

    public function getImageFile()
    {    
        return $this->imageFile;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chaussure = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add chaussure
     *
     * @param \ProprietaireBundle\Entity\Chaussure $chaussure
     *
     * @return Proprietaire
     */
    public function addChaussure(\ChaussureBundle\Entity\Chaussure $chaussure)
    {
        $this->chaussure[] = $chaussure;

        return $this;
    }

    /**
     * Remove chaussure
     *
     * @param \ProprietaireBundle\Entity\Chaussure $chaussure
     */
    public function removeChaussure(\ChaussureBundle\Entity\Chaussure $chaussure)
    {
        $this->chaussure->removeElement($chaussure);
    }

    /**
     * Get chaussure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChaussure()
    {
        return $this->chaussure;
    }

    public function __toString(){
        $contactNom = $this->nom;
        return $contactNom;
    }

    /**
     * Add vetement
     *
     * @param \VetementBundle\Entity\Vetement $vetement
     *
     * @return Proprietaire
     */
    public function addVetement(\VetementBundle\Entity\Vetement $vetement)
    {
        $this->vetement[] = $vetement;

        return $this;
    }

    /**
     * Remove vetement
     *
     * @param \VetementBundle\Entity\Vetement $vetement
     */
    public function removeVetement(\VetementBundle\Entity\Vetement $vetement)
    {
        $this->vetement->removeElement($vetement);
    }

    /**
     * Get vetement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVetement()
    {
        return $this->vetement;
    }

    /**
     * Add voiture
     *
     * @param \VoitureBundle\Entity\Voiture $voiture
     *
     * @return Proprietaire
     */
    public function addVoiture(\VoitureBundle\Entity\Voiture $voiture)
    {
        $this->voiture[] = $voiture;

        return $this;
    }

    /**
     * Remove voiture
     *
     * @param \VoitureBundle\Entity\Voiture $voiture
     */
    public function removeVoiture(\VoitureBundle\Entity\Voiture $voiture)
    {
        $this->voiture->removeElement($voiture);
    }

    /**
     * Get voiture
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoiture()
    {
        return $this->voiture;
    }

    /**
     * Add mess
     *
     * @param \CommentaireBundle\Entity\Message $mess
     *
     * @return Proprietaire
     */
    public function addMess(\CommentaireBundle\Entity\Message $mess)
    {
        $this->mess[] = $mess;

        return $this;
    }

    /**
     * Remove mess
     *
     * @param \CommentaireBundle\Entity\Message $mess
     */
    public function removeMess(\CommentaireBundle\Entity\Message $mess)
    {
        $this->mess->removeElement($mess);
    }

    /**
     * Get mess
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMess()
    {
        return $this->mess;
    }
}
