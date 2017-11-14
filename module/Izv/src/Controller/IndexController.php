<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 10.11.2017
 * Time: 13:16
 */

namespace Izv\Controller;


use Custom\BaseAdminController;
use Custom\MyForm;
use Custom\Query;
use Entity\Fields;

class IndexController extends BaseAdminController
{
    public function indexAction()
    {
        $q = new Query($this->entityManager, ['select' => 'a', 'from' => 'Fields', 'order' => 'a.id', 'desc' => 'DESC']);
        $q->setPaginator(10);
        $r = ['izv' => $q->ret('izv'), 'em' => $this->entityManager, 'fm' => $this->getFM()];
        return $r;
    }
    public function addAction()
    {
        $fields = new Fields();
        $form = new MyForm($fields, $this->entityManager);
        $this->getReq();
        $form->setAction('/izv/add/');
        if ($this->req->isPost()){
            $form->setData($this->req->getPost());
            if ($form->getForm()->isValid()){
                $this->entityManager->persist($fields);
                $this->entityManager->flush();
                $this->status = 'success';
                $this->message = "Поле добавлено";
            } else {
                $this->status = 'error';
                $this->message = 'ошибка параметров';
                foreach ($form->getForm()->getInputFilter()->getInvalidInput() as $errors) {
                    foreach ($errors->getMessages() as $error){
                        $this->message .= ' ' . $error;
                    }
                }
            }
        } else {
            $form->getForm()->prepare();
            return ['form' => $form, 'id' => $this->id];
        }
        $this->getFM();
//        return $this->redirect()->toRoute('izv');
        $this->redir('izv');
    }
    public function editAction()
    {
        $fields = new Fields();
        $id = $this->getId();
        try {
            $r = $this->entityManager->getRepository(Fields::class);
            $item = $r->find($id);
            if (empty($item)){
                $this->message = 'Поле не найдено';
                $this->status = 'error';
                $this->getFM();
                $this->redir('izv/');
            }
        } catch (\Exception $ex){
            
        }
    }
    public function deleteAction()
    {

    }
}