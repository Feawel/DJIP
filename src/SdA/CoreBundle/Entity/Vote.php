<?php
// src/SdA/CoreBundle/Entity/Vote.php

namespace SdA\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Vote as BaseVote;
use FOS\CommentBundle\Model\VotableCommentInterface;
use FOS\CommentBundle\Model\SignedVoteInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Vote extends BaseVote implements SignedVoteInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Comment of this vote
     *
     * @var Comment
     * @ORM\ManyToOne(targetEntity="SdA\CoreBundle\Entity\Comment")
     */
    protected $comment;
    
    /**
     * Author of the vote
     *
     * @ORM\ManyToOne(targetEntity="SdA\UserBundle\Entity\User")
     * @var User
     */
    protected $voter;
    
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $score = 0;
    
    /**
     * Sets the score of the comment.
     *
     * @param integer $score
     */
    public function setScore($score)
    {
    	$this->score = $score;
    }
    
    /**
     * Returns the current score of the comment.
     *
     * @return integer
     */
    public function getScore()
    {
    	return $this->score;
    }
    
    /**
     * Increments the comment score by the provided
     * value.
     *
     * @param integer value
     *
     * @return integer The new comment score
     */
    public function incrementScore($by = 1)
    {
    	$this->score += $by;
    }
    
    
    /**
     * Sets the owner of the vote
     *
     * @param string $user
     */
    public function setVoter(UserInterface $voter)
    {
    	$this->voter = $voter;
    }
    
    /**
     * Gets the owner of the vote
     *
     * @return UserInterface
     */
    public function getVoter()
    {
    	return $this->voter;
    }
}