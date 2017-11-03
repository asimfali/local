<?php

/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 27.10.2017
 * Time: 14:01
 */
namespace Admin\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Validator\ObjectExists;

class IsExistValidator
{
    protected $validator;
    protected $repository;

    public function __construct(ObjectRepository $objectRepository)
    {
        $this->repository = $objectRepository;
    }
    public function exist($value, $fields)
    {
        $this->validator = new ObjectExists([
            'object_repository' => $this->repository,
            'fields' => $fields
        ]);
        return $this->validator->isValid($value);
    }
}