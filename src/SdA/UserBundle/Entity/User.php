<?php
// src/SdA/UserBundle/Entity/User.php
 
namespace SdA\UserBundle\Entity;
 
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

 
/**
 * @ORM\Entity
 * @ORM\Table(name="sda_user")
 */
class User extends BaseUser
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
  
  /**
   * @ORM\OneToMany(targetEntity="SdA\CoreBundle\Entity\Article", mappedBy="auteur", cascade={"persist"})
   */
  private $articles;
  
  /**
   * @ORM\ManyToMany(targetEntity="SdA\CoreBundle\Entity\Article", mappedBy="followers", cascade={"persist"})
   */
  private $djipsFollowed;

  /**
  *
  * @ORM\OneToMany(targetEntity="SdA\UserBundle\Entity\User", mappedBy="favorites", cascade={"persist"})  
  */
  
  private $followers;
  
  
  
  /**
   * 
  * @ORM\ManyToOne(targetEntity="SdA\UserBundle\Entity\User", inversedBy="followers", cascade={"persist"})  
  */
  
  private $favorites;
  
  
  /**
   * @var integer
   *
   * @ORM\Column(name="nbArticles", type="integer")
   */
  private $nbArticles;

  /**
   * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
   */
  private $profilePicture;
  
  /**
   * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
   */
  private $coverPicture;
  
  public function __construct()
  {
  	parent::__construct();
  	$this->nbArticles = 0;
  	$this->articles = new \Doctrine\Common\Collections\ArrayCollection();
  	$this->djipsFollowed = new \Doctrine\Common\Collections\ArrayCollection();
  	$this->followers = new \Doctrine\Common\Collections\ArrayCollection();
  	$this->favorites = new \Doctrine\Common\Collections\ArrayCollection();

  }
  
  /**
   * @param SdA\CoreBundle\Entity\Article $article
   * @return Article
   */
  public function addArticle(\SdA\CoreBundle\Entity\Article $article)
  {
  	$this->articles[] = $article;
  	$this->nbArticles += 1;
  	return $this;
  }
  
  /**
   * @param SdA\CoreBundle\Entity\Article $article
   */
  public function removeArticle(\SdA\CoreBundle\Entity\Article $article)
  {
  	$this->articles->removeElement($article);
  	$this->nbArticles -= 1;
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection
   */
  public function getArticles()
  {
  	return $this->articles;
  }
  
  
  /**
   * @param SdA\CoreBundle\Entity\Article $article
   * @return Article
   */
  public function addDjipsFollowed(\SdA\CoreBundle\Entity\Article $article)
  {
  	$this->djipsFollowed[] = $article;
  }
  
  /**
   * @param SdA\CoreBundle\Entity\Article $article
   */
  public function removeDjipsFollowed(\SdA\CoreBundle\Entity\Article $article)
  {
  	$this->djipsFollowed->removeElement($article);
  }
  
  /**
   * @return Doctrine\Common\Collections\Collection
   */
  public function getDjipsFollowed()
  {
  	return $this->djipsFollowed;
  }
  
  
  /**
   * Set nbArticles
   *
   * @param integer $nbArticles
   * @return User
   */
  public function setNbArticles($nbArticles)
  {
  	$this->nbArticles = $nbArticles;
  
  	return $this;
  }
  
  /**
   * Get nbArticles
   *
   * @return integer
   */
  public function getNbArticles()
  {
  	return $this->nbArticles;
  }
  
  /**
   * Add followers
   *
   * @param SdA\UserBundle\Entity\User $followers
   */
  public function addFollower(\SdA\UserBundle\Entity\User $follower)
  {
  	$this->followers[] = $follower;
  }
  
  /**
   * Remove followers
   *
   * @param SdA\UserBundle\Entity\User $followers
   */
  public function removeFollower(\SdA\UserBundle\Entity\User $follower)
  {
  	$this->followers->removeElement($follower);
  }
  
  /**
   * Get followers
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getFollowers()
  {
  	return $this->followers;
  }
  
  /**
   * Add favorites
   *
   * @param SdA\UserBundle\Entity\User $favorites
   */
  public function addFavorite(\SdA\UserBundle\Entity\User $favorite)
  {
  	$this->favorites[] = $favorite;
  }
  
  /**
   * Remove favorites
   *
   * @param SdA\UserBundle\Entity\User $favorites
   */
  public function removeFavorite(\SdA\UserBundle\Entity\User $favorite)
  {
  	$this->favorites->removeElement($favorite);
  }
  
  /**
   * Get favorites
   *
   * @return Doctrine\Common\Collections\Collection
   */
  public function getFavorites()
  {
  	return $this->favorites;
  }
  
  /**
   * @param \Application\Sonata\MediaBundle\Entity\Media $profilePicture
   */
  public function setProfilePicture(\Application\Sonata\MediaBundle\Entity\Media $profilePicture)
  {
  	$this->profilePicture = $profilePicture;
  }
  
  /**
   * @return SdA\CoreBundle\Entity\Image
   */
  public function getProfilePicture()
  {
  	return $this->profilePicture;
  }
  

  /**
   * @param \Application\Sonata\MediaBundle\Entity\Media $coverPicture
   */
  public function setCoverPicture(\Application\Sonata\MediaBundle\Entity\Media $coverPicture)
  {
  	$this->coverPicture = $coverPicture;
  }
  
  /**
   * @return SdA\CoreBundle\Entity\Image
   */
  public function getCoverPicture()
  {
  	return $this->coverPicture;
  }
  
}