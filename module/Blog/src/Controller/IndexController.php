<?php

namespace Blog\Controller;

use Custom\BaseController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Entity\Article;
use Entity\Comment;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\Paginator\Paginator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class IndexController extends BaseController
{
    protected static $article = null;
    public function indexAction()
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->add('select', 'a')
            ->add('from', 'Entity\\Article a')
            ->add('where', 'a.isPublic=1')
            ->add('orderBy', 'a.id DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(2);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page',1));
        return ['articles' => $paginator];
    }
    protected function getCommentForm(Comment $comment){
        $builder = new AnnotationBuilder($this->entityManager);
        $form = $builder->createForm(new Comment());
        $form->setHydrator(new DoctrineHydrator($this->entityManager, '\Comment'));
        $form->bind($comment);
        return $form;
    }

    public function articleAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        $article = $this->entityManager->find('Entity\\Article', $id);
        self::$article = $article;
        if(empty($article)){
            return $this->notFoundAction();
        }
        $comment = new Comment();
        $form = $this->getCommentForm($comment);

        return ['article' => $article, 'form' => $form];
    }

    public function commentAddAction(){
        $comment = new Comment();
        $form = $this->getCommentForm($comment);
        $request = $this->getRequest();
        $response = $this->getResponse();
        $data = $request->getPost();
        if (!empty($data)){
            $form->setData($data);
            $messages = null;
            if (!$form->isValid()){
                $errors = $form->getMessages();
                foreach ($errors as $key=>$error) {
                    if (! empty($error) && $key != 'submit'){
                        foreach ($error as $keyer => $rower) {
                            $messages[$key][] = $rower;
                        }
                    }
                }
            }
        }
        if (! empty($messages)){
            $response->setContent(json_encode($messages));
        } else {
            $id = $data['article'];
            $rep = $this->entityManager->getRepository(Article::class);
            $article = $rep->find($id);
            $comment->setArticle($article);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->response->setContent(json_encode(['success' => 1]));
        }
        return $this->response;
    }
}
