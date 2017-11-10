<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 26.10.2017
 * Time: 9:16
 */

namespace Custom;


use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Entity\Comment;
use Entity\Role;
use Entity\User;

class CommentForm
{
    public function getCommentForm(Comment $comment, EntityManager $entityManager){
        $builder = new AnnotationBuilder($entityManager);
        $form = $builder->createForm(new Comment());
        $form->setHydrator(new DoctrineObject($entityManager, '\Comment'));
        $form->bind($comment);
        return $form;
    }
    public function getLoginForm(User $user, EntityManager $entityManager){
        $form = $this->getForm($user, $entityManager);
        $form->setAttribute('action','/auth-doctrine/index/login/');
        $form->setValidationGroup('usrName', 'usrPassword');
        return $form;
    }
    public function getRegForm(User $user, EntityManager $entityManager){
        $form = $this->getForm($user, $entityManager);
        $form->setAttribute('action','/auth-doctrine/index/register/');
        $form->get('submit')->setAttribute('value', 'Зарегистрировать');
        $form->get('usrEmail')->setAttribute('type', 'email');
        return $form;
    }
    public function getRoleForm(Role $role, EntityManager $entityManager){
        $form = $this->getForm($role, $entityManager);
        $form->setAttribute('action','/auth-doctrine/role/index');
        return $form;
    }
    public function getForm($class, EntityManager $entityManager){
        $builder = new AnnotationBuilder($entityManager);
        $name = get_class($class);
        $form = $builder->createForm(new $name());
        $form->setHydrator(new DoctrineObject($entityManager, '\\'.$name));
        $form->bind($class);
        return $form;
    }
}