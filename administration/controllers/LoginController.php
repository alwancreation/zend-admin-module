<?php

class Administration_LoginController extends Zend_Controller_Action
{

    public function init()
    {
    	
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout("layout.login");
    }

 
    public function indexAction()
    {

        $form = new Application_Form_LoginAdmin();
        $this->view->form = $form;


        if($this->getRequest()->isPost()){

            $this->view->debug_message .= 'je suis ds le if form->isValid()';

            if($form->isValid($this->getRequest()->getPost())){
                
                $authAdapter = $this->getAuthAdapter();
                $authAdapter->setIdentity($form->username->getValue())
                            ->setCredential($form->password->getValue());
                
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if($result->isValid())
                {

                    $userInfo = $authAdapter->getResultRowObject();
                    $admin_user = new Zend_Session_Namespace('admin_user');
                    $admin_user->user = $userInfo;
                    $this->_redirect('administration/index');

                }
                else
                {
                    $this->view->message = 'Erreur, Veuillez recommencer.';

                }
            }
        }
    }









    public function logoutAction()
    {
        # clear everything - session is cleared also!
        Zend_Auth::getInstance()->clearIdentity();
        $admin_user = new Zend_Session_Namespace('admin_user');        
        unset($admin_user->user);
        
        $this->redirect('administration/login');
    }

    protected function getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('user')
                    ->setIdentityColumn('username')
                    ->setCredentialColumn('password')
                    ->setCredentialTreatment('SHA1(?)');

        return $authAdapter;
    }

}

