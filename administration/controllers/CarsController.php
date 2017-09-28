<?php

class Administration_CarsController extends Zend_Controller_Action
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
        
        $this->view->page_select="categorie";
    }
	
    public function indexAction()
    {
    	$tbl_categorie=new Application_Model_DbTable_Categorie();
    	$categories=$tbl_categorie->fetchAll();
    	$request=$this->getRequest();
    	if ($request->isPost()) {
    		 
    		$data = array(
    				"libelle"=>$this->getParam("libelle",''),
    				"description"=>$this->getParam("description",''),
    		);
    		 
    		$tbl_categorie->insert($data);
    		$this->_redirect('administration/categorie');
    	}
    	
    	$this->view->categories=$categories;
    }
    
    	
    	
    public function supprimerAction()
    {
    	exit();
    }
    public function modifierAction()
    {

    	$tbl_categorie=new Application_Model_DbTable_Categorie();
    	$id=$this->getParam("id");
    	
    	$categorie=$tbl_categorie->find($id)->current();

    	if($categorie){

    		$request=$this->getRequest();
    	
    		if ($request->isPost()) {
    			
    				$data = array(
    						"libelle"=>$this->getParam("libelle",''),
    						"description"=>$this->getParam("description",''),
    				);
    	
    				$tbl_categorie->update($data,array("id=?"=>$id));
    				$this->_redirect('administration/cars/modifier/id/'.$id);
    		}
    	
    		$this->view->categorie=$categorie;
    	
    	} else {

    		$this->redirect('/administration/cars');
    	}
    	
    	
    }
    
    
}



