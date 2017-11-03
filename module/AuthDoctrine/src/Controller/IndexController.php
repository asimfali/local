<?php

/**
 * Created by PhpStorm.
 * User: Simanov
 * Date: 26.10.2017
 * Time: 9:56
 */
namespace AuthDoctrine\Controller;

use Admin\Service\IsExistValidator;
use Custom\AuthController;
use Custom\CommentForm;
use Entity\User;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\SessionManager;
use Zend\Mvc\Plugin\FlashMessenger;
use Zend\Stdlib\Message;

class IndexController extends AbstractActionController
{
    protected $authService;
    protected $entityManager;
    public function __construct($entityManager, $authenticationService)
    {
        $this->entityManager = $entityManager;
        $this->authService = $authenticationService;
    }
    public function indexAction()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        return ['users' => $users];
    }
    public function loginAction()
    {
        $user = new User();
        $forms = new CommentForm();
        $form = $forms->getLoginForm($user, $this->entityManager);
        $messages = null;
        if ($this->request->isPost()){
            $form->setData($this->request->getPost());
            if ($form->isValid()){
                $user = $form->getData();
                $authResult = $this->entityManager->getRepository(User::class)->login($user, $this->authService);
            if ($authResult->getCode() != Result::SUCCESS){
                foreach ($authResult->getMessages as $message) {
                    $messages .= $message . '\n';
                }
            } else {
                return ['auth' => $authResult];
            }
            }
        }
        return ['form' => $form, 'messages' => $messages, 'auth' => $this->authService];
    }
    public function logoutAction()
    {
        if($this->authService->hasIdentity()){
            $identity = $this->authService->getIdentity();
        }
        $this->authService->clearIdentity();
        $sm = new SessionManager();
        unset($_SESSION['auth']);
        $sm->forgetMe();

        return $this->redirect()->toRoute('auth-doctrine/default', ['controller' => 'index', 'action' => 'login']);
    }
    public function prepareData(User $user)
    {
        $user->setUsrPasswordSalt(md5(time().'setUsrPasswordSalt'));
        $user->setUsrPassword(md5('staticSalt' . $user->getUsrPassword() . $user->getUsrPasswordSalt()));
        return $user;
    }
    public function sendConfirmationEmail(User $user)
    {
//        $transport = $this->getPluginManager()->get('mail.transport');
//        $message = new Message();
    }
    public function registerAction()
    {
        $messages = null;
        $user = new User();
        $forms = new CommentForm();
        $form = $forms->getRegForm($user, $this->entityManager);
        if ($this->request->isPost()){
            $form->setData($this->request->getPost());
            $apiService = new IsExistValidator($this->entityManager->getRepository(User::class));
            if ($form->isValid()){
                if ($apiService->exist($user->getUsrName(), ['usrName'])){
                    $this->flashMessenger()->addErrorMessage('Пользователь с таким именем уже существует - '. $user->getUsrName());
                    return $this->redirect()->toRoute('auth-doctrine/default', ['controller' => 'index', 'action' => 'register']);
                }
                $this->prepareData($user);
//                $this->sendConfirmationEmail($user);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->redirect()->toRoute('auth-doctrine/default', ['controller' => 'index', 'action' => 'registration-success']);
            }
        }
        return ['form' => $form, 'messages' => $messages, 'auth' => $this->authService];
    }
    public function registrationSuccessAction()
    {
        
    }
}