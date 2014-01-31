<?php

namespace SdA\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Newslink
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Newslink
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="SdA\CoreBundle\Entity\Article", inversedBy="newslinks", cascade={"persist"})
     */
    private $article;
    
    public function __construct()
    {
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
     * Set titre
     *
     * @param string $titre
     * @return Newslink
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
     * Set url
     *
     * @param string $url
     * @return Newslink
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Set article
     *
     * @param SdA\CoreBundle\Entity\Article $article
     */
    public function setArticle(\SdA\CoreBundle\Entity\Article $article)
    {
    	$this->article = $article;
    }
    
    /**
     * Get article
     *
     * @return SdA\CoreBundle\Entity\Article
     */
    public function getArticle()
    {
    	return $this->article;
    }
}
