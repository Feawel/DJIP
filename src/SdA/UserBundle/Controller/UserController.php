<?php

namespace SdA\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SdA\UserBundle\Entity\User;
use SdA\CoreBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

	
	public function myDjipsAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		if(!is_object($user)){
			$response = $this->forward('FOSUserBundle:Security:login');
    		return $response;}
	
		return $this->render('SdAUserBundle:Profile:myDjips.html.twig', array(
				
		));
	}
	
	public function myFollowersAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
	
		if(!is_object($user)){
			$response = $this->forward('FOSUserBundle:Security:login');
			return $response;}
	
			return $this->render('SdAUserBundle:Profile:myFollowers.html.twig', array(
					
			));
	}
	
	public function myFavoritesAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
	
		if(!is_object($user)){
			$response = $this->forward('FOSUserBundle:Security:login');
			return $response;}
	
			return $this->render('SdAUserBundle:Profile:myFavorites.html.twig', array(
					
			));
	}
	
	
}
