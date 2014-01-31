<?php

namespace SdA\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\CommentBundle\Entity\Thread as BaseThread;

/**
 * Article
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="SdA\CoreBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Article extends BaseThread
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="string", length=500)
     */
    private $abstract;
    
    /**
     * @ORM\Column(name="publication", type="boolean")
     */
    private $publication;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEdition;
    
    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity="SdA\CoreBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
    
    /**
     * @ORM\ManyToMany(targetEntity="SdA\CoreBundle\Entity\Categorie", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="SdA\UserBundle\Entity\User", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;
    
    /**
     * @ORM\OneToMany(targetEntity="SdA\CoreBundle\Entity\Newslink", mappedBy="article", cascade={"persist"})
     */
    private $newslinks;
    
    /**
     * @ORM\ManyToMany(targetEntity="SdA\UserBundle\Entity\User", inversedBy="djipsFollowed", cascade={"persist"})
     */
    private $followers;
    
    
    public function __construct()
    {
    	$this->date     = new \Datetime;
    	$this->publication  = true;
    	$this->categories   = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->newslinks   = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->followers   = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->permalink = "";
    }
     
    /**
     * @ORM\prePersist
     */
    public function increase()
    {
    	$nbArticles = $this->getAuteur()->getNbArticles();
    	$this->getAuteur()->setNbArticles($nbArticles+1);
    }
    
    /**
     * @ORM\preRemove
     */
    public function decrease()
    {
    	$nbArticles = $this->getAuteur()->getNbArticles();
    	$this->getAuteur()->setNbArticles($nbArticles-1);
    }
    
    
    /**
     * @ORM\preUpdate
     * Callback pour mettre à jour la date d'édition à chaque modification de l'entit�é     */
    public function updateDate()
    {
    	$this->setDateEdition(new \Datetime());
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }
    
    /**
     * Set abstract
     *
     * @param string $abstract
     * @return Article
     */
    public function setAbstract($abstract)
    {
    	$this->abstract = $abstract;
    
    	return $this;
    }
    
    /**
     * Get abstract
     *
     * @return string
     */
    public function getAbstract()
    {
    	return $this->abstract;
    }


    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }
    
    /**
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
    	$this->publication = $publication;
    	return $this;
    }
    
    /**
     * @return boolean
     */
    public function getPublication()
    {
    	return $this->publication;
    }
    
    /**
     * @param SdA\CoreBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\SdA\CoreBundle\Entity\Image $image = null)
    {
    	$this->image = $image;
    	return $this;
    }
    
    /**
     * @return SdA\CoreBundle\Entity\Image
     */
    public function getImage()
    {
    	return $this->image;
    }
    
    /**
     * @param SdA\CoreBundle\Entity\Categorie $categorie
     * @return Article
     */
    public function addCategorie(\SdA\CoreBundle\Entity\Categorie $categorie)
    {
    	$this->categories[] = $categorie;
    	return $this;
    }
    
    /**
     * @param SdA\CoreBundle\Entity\Categorie $categorie
     */
    public function removeCategorie(\SdA\CoreBundle\Entity\Categorie $categorie)
    {
    	$this->categories->removeElement($categorie);
    }
    
    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
    	return $this->categories;
    }
    
    
    /**
     * @param datetime $dateEdition
     * @return Article
     */
    public function setDateEdition(\Datetime $dateEdition)
    {
    	$this->dateEdition = $dateEdition;
    	return $this;
    }
    
    /**
     * @return date
     */
    public function getDateEdition()
    {
    	return $this->dateEdition;
    }
    
    /**
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
    	$this->slug = $slug;
    	return $this;
    }
    
    /**
     * @return string
     */
    public function getSlug()
    {
    	return $this->slug;
    }
    
    
 
    
    /**
     * @param SdA\UserBundle\Entity\User $auteur
     * @return Article
     */
    public function setAuteur(\SdA\UserBundle\Entity\User $auteur)
    {
    	$this->auteur = $auteur;
    	return $this;
    }
    
    /**
     * @return SdA\UserBundle\Entity\User
     */
    public function getAuteur()
    {
    	return $this->auteur;
    }
    
    /**
     * @param SdA\CoreBundle\Entity\Newslink $newslink
     * @return Article
     */
    public function addNewslink(\SdA\CoreBundle\Entity\Newslink $newslink)
    {
    	$this->newslinks[] = $newslink;
    	return $this;
    }
    
    /**
     * @param SdA\CoreBundle\Entity\Newslink $newslink
     */
    public function removeNewslink(\SdA\CoreBundle\Entity\Newslink $newslink)
    {
    	$this->newslinks->removeElement($newslink);
    }
    
    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getNewslinks()
    {
    	return $this->newslinks;
    }
    
    /**
     * @param SdA\UserBundle\Entity\User $follower
     * 
     */
    public function addFollower(\SdA\UserBundle\Entity\User $follower)
    {
    	$this->followers[] = $follower;
    }
    
    /**
     * @param SdA\UserBundle\Entity\User $follower
     */
    public function removeFollower(\SdA\UserBundle\Entity\User $follower)
    {
    	$this->followers->removeElement($follower);
    }
    
    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
    	return $this->followers;
    }
    
    
}
