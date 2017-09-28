<?php

class Administration_TarifsController extends Zend_Controller_Action
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
        
        $this->view->page_select="tarifs";
    }


    public function indexAction()
    {
        $tbl_tarif=new Application_Model_DbTable_Tarif();
    	$tbl_tarif_transfert=new Application_Model_DbTable_TarifTransfert();
        $tarifs=$tbl_tarif->fetchAll();
    	$tt_tarifs=$tbl_tarif_transfert->fetchAll();
    	$request=$this->getRequest();
    	if ($request->isPost()) {
    		 if($this->getParam('add_tarif')){
                    $data = array(
                        "libelle"=>$this->getParam("libelle",""),
                        "prix_1"=>$this->getParam("prix_1",0),
                        "prix_2"=>$this->getParam("prix_2",0),
                        "prix_3"=>$this->getParam("prix_3",0),
                        "prix_4"=>$this->getParam("prix_4",0),
                        "prix_5"=>$this->getParam("prix_5",0),
                        "prix_6"=>$this->getParam("prix_6",0),
                        "prix_public"=>$this->getParam("prix_public",0),
                    );
                     
                    $tbl_tarif->insert($data);
             }
             if ($this->getParam('add_tarif_tt')) {
                $data = array(
                        "libelle"=>$this->getParam("libelle",""),
                        "prix_1"=>$this->getParam("prix_1",0),
                        "prix_2"=>$this->getParam("prix_2",0),
                        "prix_3"=>$this->getParam("prix_3",0),
                        "prix_4"=>$this->getParam("prix_4",0),
                        "prix_5"=>$this->getParam("prix_5",0),
                        "prix_6"=>$this->getParam("prix_6",0),
                        "prix_7"=>$this->getParam("prix_7",0),
                        "prix_8"=>$this->getParam("prix_8",0),
                        "prix_9"=>$this->getParam("prix_9",0),
                        "prix_10"=>$this->getParam("prix_10",0),
                    );
                    $tbl_tarif_transfert->insert($data);
             }
    		
    		$this->_redirect('administration/tarifs');
    	}
    	
        $this->view->tarifs=$tarifs;
    	$this->view->tt_tarifs=$tt_tarifs;
    }
    
    	
    	
    public function supprimerAction()
    {   
        $tbl_tarif=new Application_Model_DbTable_Tarif();
        $id=$this->getParam("id");
        
        $tarif=$tbl_tarif->find($id)->current();
        if($tarif){
            $tarif->delete();
        }
        $this->_redirect('/administration/tarifs');
        
    }
    public function ttsupprimerAction()
    {   
    	$tbl_tarif=new Application_Model_DbTable_TarifTransfert();
    	$id=$this->getParam("id");
    	
    	$tarif=$tbl_tarif->find($id)->current();
    	if($tarif){
    		$tarif->delete();
    	}
    	$this->_redirect('/administration/tarifs');
    	
    }

    public function modifierAction()
    {

        $tbl_tarif=new Application_Model_DbTable_Tarif();
        $id=$this->getParam("id");
        
        $tarif=$tbl_tarif->find($id)->current();
        if($tarif){
            $request=$this->getRequest();
        
            if ($request->isPost()) {
                
                    $data = array(
                        "libelle"=>$this->getParam("libelle",""),
                        "prix_1"=>$this->getParam("prix_1",0),
                        "prix_2"=>$this->getParam("prix_2",0),
                        "prix_3"=>$this->getParam("prix_3",0),
                        "prix_4"=>$this->getParam("prix_4",0),
                        "prix_5"=>$this->getParam("prix_5",0),
                        "prix_6"=>$this->getParam("prix_6",0),
                        "prix_public"=>$this->getParam("prix_public",0),
                    );
        
                    $tbl_tarif->update($data,array("id=?"=>$id));
                    $this->_redirect('administration/tarifs/modifier/id/'.$id);
            }
        
            $this->view->tarif=$tarif;
        
        }else{
            $this->redirect('/administration/tarifs');
        }
    }



    public function ttmodifierAction()
    {
    	$tbl_tarif=new Application_Model_DbTable_TarifTransfert();
    	$id=$this->getParam("id");
    	$tarif=$tbl_tarif->find($id)->current();
    	if($tarif){
    		$request=$this->getRequest();
    		if ($request->isPost()) {
    				$data = array(
						"libelle"=>$this->getParam("libelle",""),
                        "prix_1"=>$this->getParam("prix_1",0),
                        "prix_2"=>$this->getParam("prix_2",0),
                        "prix_3"=>$this->getParam("prix_3",0),
                        "prix_4"=>$this->getParam("prix_4",0),
                        "prix_5"=>$this->getParam("prix_5",0),
                        "prix_6"=>$this->getParam("prix_6",0),
                        "prix_7"=>$this->getParam("prix_7",0),
                        "prix_8"=>$this->getParam("prix_8",0),
                        "prix_9"=>$this->getParam("prix_9",0),
                        "prix_10"=>$this->getParam("prix_10",0),
    				);
    				$tbl_tarif->update($data,array("id=?"=>$id));
    				$this->redirect('/administration/tarifs/ttmodifier/id/'.$id);
    		}
    		$this->view->tarif=$tarif;
    	}else{
    		$this->redirect('/administration/tarifs');
    	}	
    }   
}