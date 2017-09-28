<?php

class Administration_VehiculesController extends Zend_Controller_Action
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
        
        $this->view->page_select="vehicules";
    }
	
    public function indexAction()
    {
    	$tbl_vehicules=new Application_Model_DbTable_Vehicules();
    	$vehicules=$tbl_vehicules->fetchAll();

    	$request=$this->getRequest();

    	if ($request->isPost()) {

            $image =  "" ;

            if ($_FILES['image']['size'] > 0) {

                $image = FNC::UploadImg('image', 'photos/vehicules', 120, 80);
            }
    		 
    		$data = array(

    			"titre"=>$this->getParam("libelle",''),
                "titre_en"=>$this->getParam("libelle_en",''),
    			"img"=>$image,
    		);
    		 
    		$tbl_vehicules->insert($data);
    		$this->_redirect('administration/vehicules');
    	}
    	
    	$this->view->vehicules=$vehicules;
    }
    
    	
    	
    public function supprimerAction()
    {
    	$tbl_vehicules=new Application_Model_DbTable_Vehicules();
        $id = $this->getParam("id", 0);
        $vehicule = $tbl_vehicules->find($id)->current();

        if ($vehicule) {
            
            unlink("photos/vehicules/". $vehicule->img) ;
            $vehicule->delete("id=$id");
            $this->redirect("/administration/vehicules");
        }

        exit();
    }


    public function modifierAction()
    {

    	$tbl_vehicules=new Application_Model_DbTable_Vehicules();
    	$id=$this->getParam("id");
    	
    	$vehicule=$tbl_vehicules->find($id)->current();

    	if($vehicule){

    		$request=$this->getRequest();
    	
    		if ($request->isPost()) {

                    $image =  $vehicule->img ;

                    if ($_FILES['image']['size'] > 0) {

                        $image = FNC::UploadImg('image', 'photos/vehicules', 120, 80);
                     }
    			
    				$data = array(
    						"titre"=>$this->getParam("libelle",''),
                            "titre_en"=>$this->getParam("libelle_en",''),
    						"img"=>$image,
    				);
    	
    				$tbl_vehicules->update($data,array("id=?"=>$id));
    				$this->_redirect('administration/vehicules/modifier/id/'.$id);
    		}
    	
    		$this->view->vehicule=$vehicule;
    	
    	} else {

    		$this->redirect('/administration/vehicules');
    	}
    	
    	
    }
    
    
}



