<?php

namespace SdA\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NewslinkRepository
 *
 * 
 */
class NewslinkRepository extends EntityRepository
{
	public function findAll()
	{
		return $this->findBy(array(), array('id' => 'DESC'));
	}
	
}
