<?php
// src/SdA/CoreBundle/Admin/ArticleAdmin.php

namespace SdA\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('titre', 'text', array('label' => 'Titre'))
            //->add('auteur', 'entity', array('class' => 'Acme\DemoBundle\Entity\User'))
            ->add('contenu') //if no type is specified, SonataAdminBundle tries to guess it
            ->add('date')
            ->add('auteur')
            ->add('categories')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('titre')
            ->add('categories')
            //->add('author')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('titre')
            ->add('date')
            ->add('auteur')
            ->add('nbCommentaires')
            ->add('categories')
        ;
    }
}