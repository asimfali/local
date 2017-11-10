<?php
/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 03.11.2017
 * Time: 14:19
 */

namespace AuthDoctrine\Controller;


use AuthDoctrine\Service\PermissionManager;
use Custom\BaseAdminController;

class PermissionController extends BaseAdminController
{
    /**
     * @var PermissionManager
     */
    protected $permissionManger;
    public function __construct(EntityManager $entityManager, PermissionManager $permissionManager)
    {
        $this->permissionManger = $permissionManager;
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
    public function indexAction()
    {

    }
    public function viewAction()
    {

    }
}