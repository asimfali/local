<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 23.10.2017
 * Time: 16:25
 */

namespace Admin\Filter;


use Zend\InputFilter\InputFilter;

class ArticleAddInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                'name' => 'StringLength',
                'options' => [
                    'min' => 3,
                    'max' => 100,
                ],
                ],
            ],
        ]);
        $this->add([
            'name' => 'shortArticle',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                'name' => 'StringLength',
                'options' => [
                    'max' => 800,
                ],
                ]
            ],
        ]);
        $this->add([
            'name' => 'article',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim']
            ]
        ]);
        $this->add([
            'name' => 'isPublic',
            'required' => false,
        ]);
        $this->add([
            'name' => 'category',
            'required' => true,
        ]);
    }
}