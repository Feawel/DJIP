<?php

namespace SdA\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Config\Definition\Exception\Exception;

use SdA\UserBundle\Entity\User;
use SdA\CoreBundle\Entity\Article;
use SdA\CoreBundle\Form\ArticleType;
use SdA\CoreBundle\Entity\Newslink;
use SdA\CoreBundle\Form\NewslinkType;

/**
 * Newslink controller.
 *
 */
class NewslinkController extends Controller
{

    

    /**
     * Lists all Newslink entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SdACoreBundle:Newslink')->findAll();

        return $this->render('SdACoreBundle:Newslink:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Newslink entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function createAction(Request $request)
    {
        $idArticle = $request->request->get('idArticle', '');
        $titre = $request->request->get('titre', '');
        $url = $request->request->get('url', '');

        $entity = new Newslink();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('SdACoreBundle:Article')->find($idArticle);

        if(!is_object($article))
        {
            throw new \Exception('Création d\'un newslink impossible : aucun article associé.');
        }

        if (($idArticle != '') && ($idArticle != '') && ($idArticle != '')) {
            $em = $this->getDoctrine()->getManager();

            $entity->setArticle($article);
            $entity->setTitre($titre);
            $entity->setUrl($url);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('newslink_show', array(
                'id' => $entity->getId()
                )));
        }

        return $this->render('SdACoreBundle:Newslink:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Newslink entity.
    * @Security("has_role('ROLE_AUTHOR')")
    * @param Newslink $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Newslink $entity)
    {
        $form = $this->createForm(new NewslinkType(), $entity, array(
            'action' => $this->generateUrl('newslink_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => '+', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin:4px;')));

        return $form;
    }

    /**
     * Displays a form to create a new Newslink entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function newAction()
    {
        $entity = new Newslink();
        $form   = $this->createCreateForm($entity);

        return $this->render('SdACoreBundle:Newslink:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Newslink entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SdACoreBundle:Newslink')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newslink entity.');
        }
    

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SdACoreBundle:Newslink:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
            ));
    }

    /**
     * Displays a form to edit an existing Newslink entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SdACoreBundle:Newslink')->find($id);

        $user = $this->container->get('security.context')->getToken()->getUser();
        //$author = $entity->getArticle()->getAuteur();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newslink entity.');
        }
        
        // if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        // {

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return $this->render('SdACoreBundle:Newslink:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));

        /*}
        else{
            throw new AccessDeniedException();
            
        }*/
    }

    /**
    * Creates a form to edit a Newslink entity.
    * @Security("has_role('ROLE_AUTHOR')")
    * @param Newslink $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Newslink $entity)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        /*$author = $article->getAuteur();

        if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {*/
            $form = $this->createForm(new NewslinkType(), $entity, array(
                'action' => $this->generateUrl('newslink_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));


            return $form;
        /*}
        else{
            throw new AccessDeniedException();
            
        }*/
    }
    /**
     * Edits an existing Newslink entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function updateAction(Request $request, $id)
    {
    	
        $em = $this->getDoctrine()->getManager();

        
        $entity = $em->getRepository('SdACoreBundle:Newslink')->find($id);
        $author = $entity->getArticle()->getAuteur();
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newslink entity.');
        }
       

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        

        if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {

            if ($editForm->isValid()) {
            	$em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('newslink_edit', array('id' => $id)));
            }

            return $this->render('SdACoreBundle:Newslink:show.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }
        else
        {
            throw new AccessDeniedException();
        }
    }
    /**
     * Deletes a Newslink entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function deleteAction(Request $request, $id)
    {

    	$em = $this->getDoctrine()->getManager();
    	
    	$entity = $em->getRepository('SdACoreBundle:Newslink')->find($id);
    	
    	
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        $user = $this->container->get('security.context')->getToken()->getUser();
        /*$author = $entity->getArticle()->getAuteur();

        if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {*/
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('SdACoreBundle:Newslink')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Newslink entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
            $response = $this->forward('FOSUserBundle:Profile:show');
            return $response;
        /*}
        else
        {
            return new AccessDeniedException();
        }*/
    }

    /**
     * Creates a form to delete a Newslink entity by id.
     * @Security("has_role('ROLE_AUTHOR')")
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
    	
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('newslink_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'X', 'attr' => array('class'=>'btn btn-danger', 'style' => 'font-size:7px;margin-bottom:4px;')))
            ->getForm()
        ;
    }
}
