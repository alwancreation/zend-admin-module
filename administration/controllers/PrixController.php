<?php

class Administration_PrixController extends Zend_Controller_Action
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
        
        $this->view->page_select="prix";
    }
	
    public function indexAction()
    {
    	$tbl_prix=new Application_Model_DbTable_Prix();
    	$prixs=$tbl_prix->fetchAll();
    	$request=$this->getRequest();
    	if ($request->isPost()) {
    		 
    		$data = array(
    				"libelle"=>$this->getParam("libelle",''),
    				"signe"=>$this->getParam("signe",1),
    				"val1"=>$this->getParam("val1",0),
    				"val2"=>$this->getParam("val2",0),
    				"ordre"=>$this->getParam("ordre",0),
    		
    		);
    		 
    		$tbl_prix->insert($data);
    		$this->_redirect('administration/prix');
    	}
    	
    	$this->view->prices=$prixs;
    }
    
    	
    	
    public function supprimerAction()
    {   
    	$tbl_prix=new Application_Model_DbTable_Prix();
    	$id=$this->getParam("id");
    	
    	$prix=$tbl_prix->find($id)->current();
    	if($prix){
    		$prix->delete();
    	}
    	$this->_redirect('administration/prix');
    	
    }
    public function modifierAction()
    {

    	$tbl_prix=new Application_Model_DbTable_Prix();
    	$id=$this->getParam("id");
    	
    	$prix=$tbl_prix->find($id)->current();
    	if($prix){
    		$request=$this->getRequest();
    	
    		if ($request->isPost()) {
    			
    				$data = array(
    						"libelle"=>$this->getParam("libelle",''),
		    				"signe"=>$this->getParam("signe",1),
		    				"val1"=>$this->getParam("val1",0),
		    				"val2"=>$this->getParam("val2",0),
		    				"ordre"=>$this->getParam("ordre",0),
    				);
    	
    				$tbl_prix->update($data,array("id=?"=>$id));
    				$this->_redirect('administration/prix/modifier/id/'.$id);
    		}
    	
    		$this->view->prix=$prix;
    	
    	}else{
    		$this->redirect('/administration/prix');
    	}
    	
    	
    }
    
    
}



