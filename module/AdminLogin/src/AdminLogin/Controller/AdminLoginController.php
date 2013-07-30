<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eyapici
 * Date: 26.06.2013
 * Time: 14:22
 * To change this template use File | Settings | File Templates.
 */
namespace AdminLogin\Controller;
use Zend\Authentication\Storage\Session;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result as Result;


class AdminLoginController extends AbstractActionController{
    private $authServis;
    public function __construct(){
        $this->authServis = new AuthenticationService();
    }

    public function indexAction(){
        $authService = $this->authServis;
        if($authService->getIdentity()){
            return $this->redirect()->toRoute('users');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            // get post data
            $post = $request->getPost();

            // get the db adapter
            $sm = $this->getServiceLocator();
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

            // create auth adapter
            $authAdapter = new AuthAdapter($dbAdapter);

            // configure auth adapter
            $authAdapter->setTableName('users')
                ->setIdentityColumn('username')
                ->setCredentialColumn('password')
                ->setIdentityColumn('state');

            // pass authentication information to auth adapter
            $authAdapter->setIdentity($post->get('username'))
                        ->setCredential(md5($post->get('password')))
                        ->setIdentity(1);


            // create auth service and set adapter
            // auth services provides storage after authenticate
            $authService->setAdapter($authAdapter);

            // authenticate
            $result = $authService->authenticate();

            // check if authentication was successful
            // if authentication was successful, user information is stored automatically by adapter
            if ($result->isValid()) {
                // redirect to user index page

                return $this->redirect()->toRoute('users');
            } else {
                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        /** do stuff for nonexistent identity * */
                        break;

                    case Result::FAILURE_CREDENTIAL_INVALID:
                        /** do stuff for invalid credential * */
                        break;

                    case Result::SUCCESS:
                        /** do stuff for successful authentication * */
                        break;

                    default:
                        /** do stuff for other failure * */
                        break;
                }
            }
        }
        $this->layout('layout/adminlogin');
        return array();
    }

    public function logoutAction(){
        $this->authServis->clearIdentity();
        return $this->redirect()->toRoute();
    }
}