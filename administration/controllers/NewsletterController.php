<?php

class Administration_NewsletterController extends Zend_Controller_Action
{

    public function init()
    {
    	$admin_user = new Zend_Session_Namespace('admin_user');
        if(!Zend_Auth::getInstance()->hasIdentity() or !isset($admin_user->user))
        {
            $this->_redirect('administration/login');
        }
        
        /* Initialize action controller here */
        
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH."/layouts/scripts/admin/");
        
        $this->view->page_select="newsletter";
    }
	
    public function indexAction()
    {
    	$tbl_newsletter=new Application_Model_DbTable_Newsletter();
    	$newsletters=$tbl_newsletter->fetchAll();
    
    	$this->view->emails=$newsletters;
    }
    
    	
    	
    public function supprimerAction()
    {
    	$tbl_newsletter=new Application_Model_DbTable_Newsletter();
    	$tbl_newsletter->delete(array("id=?"=>$this->getParam("id",0)));
    	$this->_redirect('administration/newsletter');
    	
    }
    
    
    
}



