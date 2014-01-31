<?php

namespace SdA\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SdA\CoreBundle\Entity\Article;
use SdA\CoreBundle\Entity\Commentaire;
use SdA\CoreBundle\Form\ArticleType;
use SdA\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{

 /**
	 * Fonction permettant d'ajouter l'utilisateur courant en tant que follower d'un DJIP
	 * Agit en XmlHTTPRequest (POST avec 'id' de l'article)
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function removeFollowedDjipAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
	
		if(!is_object($user)){
			$response = $this->forward('FOSUserBundle:Security:login');
			return $response;}
	
			$request = $this->container->get('request');
			 
			if($request->isXmlHttpRequest())
			{
	
				$id = $request->request->get('id');
	
				$em = $this->getDoctrine()->getManager();
				$article = $em->getRepository('SdACoreBundle:Article')->find($id);
				$article->removeFollower($user);
	
				$em->persist($user);
				$em->persist($article);
				$em->flush();
			}
	
			
			return new Response(1);
	}
	
/**
     * Fonction permettant de retirer l'utilisateur courant en tant que follower d'un DJIP
     * Agit en XmlHTTPRequest (POST avec 'id" de l'article)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addFollowedDjipAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
    
        if(!is_object($user)){
            $response = $this->forward('FOSUserBundle:Security:login');
            return $response;}
    
            $request = $this->container->get('request');
             
            if($request->isXmlHttpRequest())
            {
    
                $id = $request->request->get('id');
    
                $em = $this->getDoctrine()->getManager();
                $article = $em->getRepository('SdACoreBundle:Article')->find($id);
                $article->addFollower($user);
    
                $em->persist($user);
                $em->persist($article);
                $em->flush();
            }
    
            return new Response(1);
    }

	/**
	 * Renvoie le dashboard de l'utilisateur
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function indexAction()
    {
        $menuSelected = 'hot';
    	//On récupère les djips triés par odre décroissant de création
    	$em = $this->getDoctrine()->getManager();
    	$articles = $em->getRepository('SdACoreBundle:Article')->findAll();
    	
    	//On récupère les différentes catégories
    	$categories = $em->getRepository('SdACoreBundle:Categorie')->findAll();
    	
    	
        return $this->render('SdACoreBundle:Default:index.html.twig', array(
        		'articles' => $articles,
        		'categories' => $categories,
                'menuSelected' => $menuSelected
        		
        ));
    }
    
    /**
     * Renvoie le dashboard utilisateur filtré par 1 catégorie
     * @param string $categorie
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexCategorieAction($categorie)
    {
    	$em = $this->getDoctrine()->getManager();
    	
        $menuSelected = $categorie;
        if ($categorie == 'myDjips') {
            $user = $this->container->get('security.context')->getToken()->getUser();
            if (is_object($user)) {
                $articles = $user->getArticles();
            }
            else
            {
                return $this->forward('FOSUserBundle:Security:login');
            }
        }
        else
        {
            $articles = $em->getRepository('SdACoreBundle:Article')->getArticlesByCategorie($categorie);   
        }
    	$categories = $em->getRepository('SdACoreBundle:Categorie')->findAll(); 
    	 
    	return $this->render('SdACoreBundle:Default:index.html.twig', array(
    			'articles' => $articles,
    			'categories' => $categories,
                'menuSelected' => $menuSelected
    	));
    }
    
    /**
     * Renvoie un template DJIP
	 * Agit en XmlHTTPRequest (POST avec 'id" du DJIP)
	 * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function djipAction()
    {
    	$request = $this->container->get('request');

    	
    	$user = $this->container->get('security.context')->getToken()->getUser();
    	
    	if($request->isXmlHttpRequest())
    	{

    		$id = $request->request->get('id');
    		
    		$em = $this->getDoctrine()->getManager();
    		$article = $em->getRepository('SdACoreBundle:Article')->find($id);

            $newslinks = $article->getNewslinks();
    		
    		$followers = $article->getFollowers();
    		$auteur = $article->getAuteur();

    		$isFollower = false;
            $isAuteur = false;
            
            if(is_object($user) && (sizeof($followers) > 0)){
        		foreach ($followers as $follower){
        			if($follower->getUsername() == $user->getUsername())
        			{
        				$isFollower = true;
        				break;
        			}
        			
        		}
            }
 
             if(is_object($user) && is_object($auteur)){
                if ($user->getUsername() == $auteur->getUsername()) {
                    $isAuteur = true;
                }
                    
            }
           	
    		return $this->render('SdACoreBundle:Default:djip.html.twig', array(
    				'article' => $article,
    				'isFollower' => $isFollower,
                    'isAuteur' => $isAuteur,
                    'newslinks' => $newslinks
    		));
    	}
        return new Response('Yop');
    }
    
    
    public function djipDisplayAction($id)
    { 
    	
    
    		$em = $this->getDoctrine()->getManager();
    		$article = $em->getRepository('SdACoreBundle:Article')->find($id);
            $user = $this->container->get('security.context')->getToken()->getUser();

            

            
            $followers = $article->getFollowers();
            $auteur = $article->getAuteur();

            $isFollower = false;
            $isAuteur = false;
            
            if(is_object($user) && (sizeof($followers) > 0)){
                foreach ($followers as $follower){
                    if($follower->getUsername() == $user->getUsername())
                    {
                        $isFollower = true;
                        break;
                    }
                    
                }
            }
 
             if(is_object($user) && is_object($auteur)){
                if ($user->getUsername() == $auteur->getUsername()) {
                    $isAuteur = true;
                }
                    
            }
    
    		return $this->render('SdACoreBundle:Default:djip.html.twig', array('article' => $article, 'isFollower' => $isFollower, 'isAuteur' => $isAuteur));
    }
    
    
    
    
    
    
    
    /**
     * Création de données de test
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createArticlesAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('SdACoreBundle:Article');
    
    	$repoAuteur = $em->getRepository('SdAUserBundle:User');
    	$auteur = $repoAuteur->find(1);
    
    	$repoCategorie = $em->getRepository('SdACoreBundle:Categorie');
    	$politique = $repoCategorie->find(1);
    	$international = $repoCategorie->find(2);
    	$science = $repoCategorie->find(3);
    	$techno = $repoCategorie->find(4);
    	$societe = $repoCategorie->find(5);
    	$culture = $repoCategorie->find(6);
    
    	$auteur2 = $repoAuteur->find(2);
    	$auteur3 = $repoAuteur->find(3);
    	$auteur4 = $repoAuteur->find(4);
    	$auteur5 = $repoAuteur->find(5);
    
    	$tab = array();
    	for ($i=0;$i<40;$i++){
    		$tab[] = 'Article test ' . $i;
    			
    		$article = new Article();
    		$article->setTitre($tab[$i]);
    		$article->setContenu($tab[$i]);
    			
    		if($i%5 == 0)
    			$article->setAuteur($auteur2);
    		elseif($i%5 == 1)
    		$article->setAuteur($auteur3);
    		elseif($i%5 == 2)
    		$article->setAuteur($auteur4);
    		elseif($i%5 == 3)
    		$article->setAuteur($auteur5);
    		elseif($i%5 == 4)
    		$article->setAuteur($auteur);
    			
    		if($i%6 == 0)
    			$article->addCategorie($politique);
    		elseif ($i%6 == 1)
    		$article->addCategorie($international);
    		elseif ($i%6 == 2)
    		$article->addCategorie($science);
    		elseif ($i%6 == 3)
    		$article->addCategorie($techno);
    		elseif ($i%6 == 4)
    		$article->addCategorie($societe);
    		elseif ($i%6 == 5)
    		$article->addCategorie($culture);
    			
    		$em->persist($article);
    	}
    
    	$em->flush();
    
    	return new Response('OK');
    
    
    
    
    }
}
