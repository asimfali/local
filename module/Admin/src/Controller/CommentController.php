<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 26.10.2017
 * Time: 8:53
 */

namespace Admin\Controller;

use Custom\CommentForm;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Custom\BaseController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Entity\Comment;
use Zend\Paginator\Paginator;

class CommentController extends BaseController
{
    public function indexAction()
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('a')
            ->from('Entity\Comment', 'a')
            ->orderBy('a.id', 'DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page',1));
        return ['comments' => $paginator];
    }
    public function editAction()
    {
        $status = $message = '';
        $id = (int) $this->params()->fromRoute('id',0);
        $rep = $this->entityManager->getRepository(Comment::class);
        $comment =  $rep->find($id);
        if (empty($comment)){
            $message = 'Комментарий не найден';
            $status = 'error';
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
            return $this->redirect()->toRoute('admin/comment');
        }
        $commentForm = new CommentForm();
        $form = $commentForm->getCommentForm($comment, $this->entityManager);
        if ($this->request->isPost()){
            $form->setData($this->request->getPost());
            if ($form->isValid()){
                $this->entityManager->persist($comment);
                $this->entityManager->flush();
                $status = 'success';
                $message = 'Комментарий обновлен';
            } else {
                $status = 'erroe';
                $message = 'ошибка параметров';
                foreach ($form->getInputFilter()->getInvalidInput() as $error) {
                    foreach ($error->getMessages() as $item) {
                        $message .= ' ' . $item;
                    }
                }
            }
        } else {
            return ['form' => $form, 'id' => $id, 'comment' => $comment];
        }

        if($message){
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }
        return $this->redirect()->toRoute('admin/comment');
    }
    public function deleteAction(){
        $status = $message = '';
        $id = (int) $this->params()->fromRoute('id',0);
        try{
            $rep = $this->entityManager->getRepository(Comment::class);
            $item =  $rep->find($id);
            $this->entityManager->remove($item);
            $this->entityManager->flush();
            $status = 'success';
            $message = 'Комментарий удален';
        }catch (\Exception $ex){
            $status = 'error';
            $message = 'Ошибка удаления записи: ' . $ex->getMessage();
        }
        $this->flashMessenger()
            ->setNamespace($status)
            ->addMessage($message);
        return $this->redirect()->toRoute('admin/comment');
    }
}