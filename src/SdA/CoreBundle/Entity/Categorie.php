<?php

namespace SdA\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SdA\CoreBundle\Entity\CategorieRepository")
 */
class Categorie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    
    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity="SdA\CoreBundle\Entity\Article", cascade={"persist"})
     */
    private $articles;
    
    public function __construct()
    {
    	$this->articles   = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Categorie
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
     * Set color
     *
     * @param string $color
     * @return Categorie
     */
    public function setColor($color)
    {
    	$this->color = $color;
    
    	return $this;
    }
    
    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
    	return $this->color;
    }
    
    /**
     * @param SdA\CoreBundle\Entity\Article $article
     * @return Categorie
     */
    public function addArticle(\SdA\CoreBundle\Entity\Article $article)
    {
    	$this->articles[] = $article;
    	return $this;
    }
    
    /**
     * @param SdA\CoreBundle\Entity\Article $article
     */
    public function removeArticle(\SdA\CoreBundle\Entity\Article $article)
    {
    	$this->articles->removeElement($article);
    }
    
    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
    	return $this->articles;
    }
    
    public function __toString()
    {
    	return $this->getNom();
    }
}
