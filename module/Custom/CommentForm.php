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

class CommentForm
{
    public function getCommentForm(Comment $comment, EntityManager $entityManager){
        $builder = new AnnotationBuilder($entityManager);
        $form = $builder->createForm(new Comment());
        $form->setHydrator(new DoctrineObject($entityManager, '\Comment'));
        $form->bind($comment);
        return $form;
    }
}