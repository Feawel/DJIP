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

/**
 * Article controller.
 *
 */
class ArticleController extends Controller
{

    

    /**
     * Lists all Article entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SdACoreBundle:Article')->findAll();

        return $this->render('SdACoreBundle:Article:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Article entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function createAction(Request $request)
    {
        $entity = new Article();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->container->get('security.context')->getToken()->getUser();
            if(!is_object($user)){
            	throw new \Exception('Inscrivez vous avant de pouvoir poster un DJIP !! ;)');
            }
            $entity->setAuteur($user);
            
            if (sizeof($entity->getCategories()) == 0) {
                throw new Exception("Choisir au moins une catégorie pour votre DJIP !", 1);
                
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('article_show', array('slug' => $entity->getSlug())));
        }

        return $this->render('SdACoreBundle:Article:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Article entity.
    * @Security("has_role('ROLE_AUTHOR')")
    * @param Article $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Article $entity)
    {
        $form = $this->createForm(new ArticleType(), $entity, array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'CREER UN DJIP !', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-left:380px;')));

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function newAction()
    {
        $entity = new Article();
        $form   = $this->createCreateForm($entity);

        return $this->render('SdACoreBundle:Article:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Article entity.
     *
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SdACoreBundle:Article')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }
     
        $id = $entity->getId();

        $deleteForm = $this->createDeleteForm($slug);

        return $this->render('SdACoreBundle:Article:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        
            ));
    }

    /**
     * Displays a form to edit an existing Article entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SdACoreBundle:Article')->findOneBySlug($slug);

        $user = $this->container->get('security.context')->getToken()->getUser();
        $author = $entity->getAuteur();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }
        
        if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $id = $entity->getId();

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($slug);

            return $this->render('SdACoreBundle:Article:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));

        }
        else{
            throw new AccessDeniedException();
            
        }
    }

    /**
    * Creates a form to edit a Article entity.
    * @Security("has_role('ROLE_AUTHOR')")
    * @param Article $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Article $entity)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $author = $entity->getAuteur();

        if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $form = $this->createForm(new ArticleType(), $entity, array(
                'action' => $this->generateUrl('article_update', array('slug' => $entity->getSlug())),
                'method' => 'PUT',
            ));


            return $form;
        }
        else{
            throw new AccessDeniedException();
            
        }
    }
    /**
     * Edits an existing Article entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function updateAction(Request $request, $slug)
    {
    	
        $em = $this->getDoctrine()->getManager();

        
        $entity = $em->getRepository('SdACoreBundle:Article')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }
        
        $id = $entity->getId();

        $deleteForm = $this->createDeleteForm($slug);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
        $user = $this->container->get('security.context')->getToken()->getUser();
        $author = $entity->getAuteur();

        if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {

            if ($editForm->isValid()) {
            	$em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('article_edit', array('slug' => $slug)));
            }

            return $this->render('SdACoreBundle:Article:show.html.twig', array(
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
     * Deletes a Article entity.
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function deleteAction(Request $request, $slug)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$entity = $em->getRepository('SdACoreBundle:Article')->findOneBySlug($slug);
        $id = $entity->getId();
    	
    	
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        $user = $this->container->get('security.context')->getToken()->getUser();
        $author = $entity->getAuteur();

        if($author == $user || $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('SdACoreBundle:Article')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Article entity.');
                }

                $em->remove($entity);
                $em->flush();
            }
            $response = $this->forward('FOSUserBundle:Profile:show');
            return $response;
        }
        else
        {
            return new AccessDeniedException();
        }
    }

    /**
     * Creates a form to delete a Article entity by id.
     * @Security("has_role('ROLE_AUTHOR')")
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($slug)
    {
    	
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('article_delete', array('slug' => $slug)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer ce DJIP', 'attr' => array('class'=>'btn btn-danger')))
            ->getForm()
        ;
    }
}
