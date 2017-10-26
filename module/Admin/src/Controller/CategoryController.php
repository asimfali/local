<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 19.10.2017
 * Time: 14:55
 */

namespace Admin\Controller;
//use Custom\BaseAdminController as BaseController;
use Admin\Form\CategoryAddForm;
use Custom\BaseController;
use Doctrine\ORM\EntityManager;
use Entity\Category;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class CategoryController extends BaseController
{
    public $id = 0;
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function onDispatch(MvcEvent $e)
    {
        $r = parent::onDispatch($e);
        $this->layout()->setTemplate('layout/admin_layout');
        return $r;
    }

    public function indexAction()
    {
        $this->query = $this->entityManager->createQuery('SELECT u FROM Entity\Category u ORDER BY u.id DESC');
        $rows = $this->query->getResult();
//        $rep = $this->entityManager->getRepository(Category::class);
//        $all = $rep->findAll();
        return array('category' => $rows);
    }

    public function addAction()
    {
        $form = new CategoryAddForm();
        $status = $message = '';
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setData($this->request->getPost());
            if ($form->isValid()){
                $category = new Category();
                $category->exchangeArray($form->getData());
                $this->entityManager->persist($category);
                $this->entityManager->flush();
                $status = 'success';
                $message = 'Категория добавлена';
            } else {
                $status = 'erroe';
                $message = 'ошибка параметров';
            }
        } else {
            return ['form' => $form];
        }
        if ($message){
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }
        return $this->redirect()->toRoute('admin/category');
    }

    public function editAction(){
        $form = new CategoryAddForm();
        $status = $message = '';
        $id = (int) $this->params()->fromRoute('id',0);
        $rep = $this->entityManager->getRepository(Category::class);
        $category =  $rep->find($id);
        if (empty($category)){
            $message = 'Категория не найдена';
            $status = 'error';
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
            return $this->redirect()->toRoute('admin/category');
        }
        $form->bind($category);
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setData($this->request->getPost());
            if ($form->isValid()){
                $this->entityManager->persist($category);
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
            return ['form' => $form];
        }

        if($message){
            $this->flashMessenger()
                ->setNamespace($status)
                ->addMessage($message);
        }
        return $this->redirect()->toRoute('admin/category');
    }

    public function deleteAction(){
        $status = $message = '';
        $id = (int) $this->params()->fromRoute('id',0);
        try{
            $rep = $this->entityManager->getRepository(Category::class);
            $item =  $rep->find($id);
            $this->entityManager->remove($item);
            $this->entityManager->flush();
            $status = 'success';
            $message = 'Категория удалена';
        }catch (\Exception $ex){
            $status = 'error';
            $message = 'Ошибка удаления записи: ' . $ex->getMessage();
        }
        $this->flashMessenger()
            ->setNamespace($status)
            ->addMessage($message);
        return $this->redirect()->toRoute('admin/category');
    }
}