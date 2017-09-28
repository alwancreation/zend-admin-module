<?php

class Administration_DestinationController extends Zend_Controller_Action
{

    public function init()
    {
    	$admin_user = new Zend_Session_Namespace('admin_user');
        if(!Zend_Auth::getInstance()->hasIdentity() or !isset($admin_user->user))
        {
            $this->redirect('administration/login');
        }
        
        /* Initialize action controller here */
        
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH."/layouts/scripts/admin/");
        
        $this->view->page_select="destination";
    }
	
    public function indexAction()
    {
    	$tbl_destination=new Application_Model_DbTable_Destination();
    	$destinations=$tbl_destination->fetchAll();
    	$request=$this->getRequest();
    	if ($request->isPost()) {
    		 
    		$data = array(
    				"libelle"=>$this->getParam("libelle",''),
                    "libelle_en"=>$this->getParam("libelle_en",''),
    				"description"=>$this->getParam("description",''),
    		);
    		 
    		$tbl_destination->insert($data);
    		$this->redirect('/administration/destination');
    	}
    	
    	$this->view->destinations=$destinations;
    }
    
    	
    	
    public function supprimerAction()
    {
        $tbl_destination=new Application_Model_DbTable_Destination();
        $line = $tbl_destination->find($this->getParam("id",0))->current();
        if($line){
            $tbl_destination->delete(array("id=?"=>$line->id));
            $this->redirect("/administration/destination");
        }
        throw new Zend_Controller_Action_Exception("Error Processing Request", 404);

    	
    }

    public function modifierAction()
    {

    	$tbl_destination=new Application_Model_DbTable_Destination();
    	$id=$this->getParam("id");
    	
    	$destination=$tbl_destination->find($id)->current();
    	if($destination){
    		$request=$this->getRequest();
    	
    		if ($request->isPost()) {
    			
    				$data = array(
    						"libelle"=>$this->getParam("libelle",''),
                            "libelle_en"=>$this->getParam("libelle_en",''),
    						"description"=>$this->getParam("description",''),

    				);

    				$tbl_destination->update($data,array("id=?"=>$id));
    				$this->redirect('administration/destination/modifier/id/'.$id);
    		}
    	
    		$this->view->destination=$destination;
    	
    	}else{
    		$this->redirect('/administration/destination');
    	}
    	
    	
    }


    //

     public function lieuxAction()
    {

        $tbl_lieu= new Application_Model_DbTable_Lieu();
        $id_ville=$this->getParam("id");

        $request=$this->getRequest();
        if ($request->isPost()) {
        
            $data = array(
                    "libelle" => $this->getParam("libelle",''),
                    "libelle_en" => $this->getParam("libelle_en",''),
                    "parent_id" => $id_ville,
                "is_airport"=>$this->getParam("is_airport",0),
            );
             
            $tbl_lieu->insert($data);
            // $this->redirect('administration/destination');
        }

        $destinations=$tbl_lieu->fetchAll("parent_id=$id_ville");
        $this->view->destinations=$destinations;
        
        
    }


    public function modifierlieuAction()
    {

        $tbl_destination=new Application_Model_DbTable_Lieu();
        $id=$this->getParam("id");
        $destination=$tbl_destination->find($id)->current();
        $request=$this->getRequest();
        $parent_id = $destination->parent_id;
        $this->view->destination=$destination;

        if ($request->isPost()) {
            
                $data = array(
                    "libelle"=>$this->getParam("libelle",''),
                    "libelle_en"=>$this->getParam("libelle_en",''),
                    "is_airport"=>$this->getParam("is_airport",0),
                );
    
                $tbl_destination->update($data,array("id=?"=>$id));
                $this->redirect('administration/destination/lieux/id/'.$parent_id);
        }
        
        
        
    }


    public function supprimerlieuAction()
    {
        $produit=new Application_Model_DbTable_Lieu();
        $id=$this->getParam("id",0);
        $produit=$produit->find($id)->current();
        $parent_id = $produit->parent_id;
        // echo " // " . $produit->parent_id . " // " ;
        if($produit){

            $produit->delete();
            $this->redirect('administration/destination/lieux/id/'.$parent_id);

        }

        exit();
    }
    
    
}



