<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 03.11.2017
 * Time: 14:15
 */

namespace AuthDoctrine\Controller;


use AuthDoctrine\Service\RoleManager;
use Custom\BaseAdminController;
use Doctrine\ORM\EntityManager;
use Entity\Role;
use Zend\View\Model\ViewModel;

class RoleController extends BaseAdminController
{
    /**
     * @var RoleManager
     */
    private $roleManager;

    /**
     * RoleController constructor.
     * @param EntityManager $entityManager
     * @param RoleManager $roleManager
     */
    public function __construct(EntityManager $entityManager, RoleManager $roleManager)
    {
        $this->roleManager = $roleManager;
        parent::__construct($entityManager);
    }

    public function addAction()
    {
    
    }
    public function deleteAction()
    {

    }
    public function editAction()
    {

    }
    public function editPermissionAction()
    {

    }
    public function indexAction()
    {
        $roles = $this->entityManager->getRepository(Role::class)
            ->findBy([],['id' => 'ASC']);
        return new ViewModel([
            'roles' => $roles
        ]);
    }
    public function viewAction()
    {

    }
}