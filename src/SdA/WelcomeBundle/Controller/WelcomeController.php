<?php

namespace SdA\WelcomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('SdAWelcomeBundle:Welcome:index.html.twig');
    }
    
    public function aboutAction()
    {
    	return $this->render('SdAWelcomeBundle:Welcome:about.html.twig');
    }
}
