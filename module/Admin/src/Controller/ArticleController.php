<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 23.10.2017
 * Time: 12:44
 */
namespace Admin\Controller;

use Admin\Form\ArticleAddForm;
use Custom\BaseController;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Entity\Article;
use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator;

class ArticleController extends BaseController
{
    public function onDispatch(MvcEvent $e)
    {
        $r = parent::onDispatch($e);
        $this->layout()->setTemplate('layout/admin_layout');
        return $r;
    }

    public function indexAction()
    {
        $query = $this->entityManager->createQueryBuilder();
        $query
            ->select('a')
            ->from('Entity\Article', 'a')
            ->orderBy('a.id', 'DESC');
//        $paginator = $this->paginate($query, 10, $this->params()->fromQuery('page', 1));
        $adapter = new DoctrinePaginator(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page',1));
        return ['articles' => $paginator];
    }
    public function addAction(){
        $form = new ArticleAddForm($this->entityManager);
        $status = $message = '';
        $request = $this->getRequest();
        if ($request->isPost()){
            $data = $request->getPost();
            $article = new Article();
            $form->setHydrator(new DoctrineHydrator($this->entityManager, '\Article'));
            $form->bind($article);
            $form->setData($data);
            if ($form->isValid()){
                $this->entityManager->persist($article);
                $this->entityManager->flush();
                $status = 'success';
                $message = 'Категория добавлена';
            } else {
                $status = 'erroe';
                $message = 'ошибка параметров';
                foreach ($form->getInputFilter()->getInvalidInput() as $errors) {
                    foreach ($errors->getMessages() as $error){
                        $message .= ' ' . $error;
                    }
                }
            }
        } else {
            return ['form' => $form];
        }
        if ($message){
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }
        return $this->redirect()->toRoute('admin/article');
    }
    public function editAction(){
        $form = new ArticleAddForm($this->entityManager);
        $status = $message = '';
        $id = (int) $this->params()->fromRoute('id',0);
        $rep = $this->entityManager->getRepository(Article::class);
        $article =  $rep->find($id);
        if (empty($article)){
            $message = 'Статья не найдена';
            $status = 'error';
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
            return $this->redirect()->toRoute('admin/article');
        }
        $form->setHydrator(new DoctrineHydrator($this->entityManager, '\Article'));
        $form->bind($article);
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setData($this->request->getPost());
            if ($form->isValid()){
                $this->entityManager->persist($article);
                $this->entityManager->flush();
                $status = 'success';
                $message = 'Категория обновлена';
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
            return ['form' => $form, 'id' => $id];
        }

        if($message){
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }
        return $this->redirect()->toRoute('admin/article');
    }
    public function deleteAction(){
        $status = $message = '';
        $id = (int) $this->params()->fromRoute('id',0);
        try{
            $rep = $this->entityManager->getRepository(Article::class);
            $item =  $rep->find($id);
            $this->entityManager->remove($item);
            $this->entityManager->flush();
            $status = 'success';
            $message = 'Статья удалена';
        }catch (\Exception $ex){
            $status = 'error';
            $message = 'Ошибка удаления записи: ' . $ex->getMessage();
        }
        $this->flashMessenger()
            ->setNamespace($status)
            ->addMessage($message);
        return $this->redirect()->toRoute('admin/article');
    }
    public function paginate($dql, $pageSize = 10, $currentPage = 1){
        $paginator = new ORMPaginator($dql);
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($currentPage - 1))
            ->setMaxResults($pageSize);
        return $paginator;
    }
}