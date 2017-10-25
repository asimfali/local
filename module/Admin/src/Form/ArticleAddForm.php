<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 23.10.2017
 * Time: 15:59
 */
namespace Admin\Form;
use Admin\Filter\ArticleAddInputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\Form\Form;

class ArticleAddForm extends Form implements ObjectManagerAwareInterface{
    protected $objectManager;
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('articleAddForm');
        $this->setObjectManager($objectManager);
        $this->createElements();
    }
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }
    public function createElements(){
        $this->setAttribute('method','post');
        $this->setAttribute('class', 'bs-example form-horizontal');
        $this->setInputFilter(new ArticleAddInputFilter());
        $this->add([
            'name' => 'category',
            'type' => 'DoctrineModule\\Form\\Element\\ObjectSelect',
            'options' => [
                'label' => 'Категория',
                'empty_option' => 'Выберите категорию...',
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Entity\Category',
                'property' => 'categoryName',
            ],
            'attributes' => [
                'class' => 'form-control',
                'required' => 'required'
            ]
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'Text',
            'options' => [
                'min' => 3,
                'max' => 100,
                'label' => 'Заголовок',
            ],
            'attributes' => [
                'class' => 'form-control',
                'required' => 'required'
            ]
        ]);
        $this->add([
            'name' => 'shortArticle',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Начало статьи',
            ],
            'attributes' => [
                'class' => 'form-control ckeditor',
            ]
        ]);
        $this->add([
            'name' => 'article',
            'type' => 'Textarea',
            'options' => [
                'label' => 'Статья',
            ],
            'attributes' => [
                'class' => 'form-control ckeditor',
                'required' => 'required'
            ]
        ]);
        $this->add([
            'name' => 'isPublic',
            'type' => 'Checkbox',
            'options' => [
                'label' => 'Опубликовать',
                'use_hidden_Element' => true,
                'checked_value' => 1,
                'unchecked_value' => 0,
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Сохранить',
                'id' => 'btn_submit',
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}