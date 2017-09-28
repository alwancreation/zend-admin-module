<?php

class Administration_TransfertsController extends Zend_Controller_Action
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
        
        $this->view->page_select="transferts";
    }
public function supprimerjourAction()
    {
    	$tbl_jour=new Application_Model_DbTable_Jour();
    	$idjour=$this->getParam("id",0);
    	$jour=$tbl_jour->find($idjour)->current();
    	if($jour){
    		$id_transfert=$jour->transfert_id;
    		$jour->delete();
    		$this->redirect("/administration/transferts/modifier/id/".$id_transfert.'/ong/jours');
    	}
    	exit();
    }
    
    public function supprimerAction()
    {
    	$tbl_transfert =new Application_Model_DbTable_Transfert();
    	$id=$this->getParam("id",0);
    	$transfert=$tbl_transfert->find($id);
    	if($transfert){
    		
    		$transfert->delete();
    		$this->redirect("/administration/transferts");
    	}
    	exit();
    }
    
    public function indexAction()
    {

    	$tbl_transfert= new Application_Model_DbTable_Transfert();
        $tbl_vehicule= new Application_Model_DbTable_Vehicules();
    	$tbl_destination= new Application_Model_DbTable_Destination();
        $tbl_tarif_transfert=new Application_Model_DbTable_TarifTransfert();
        $tbl_lieu = new Application_Model_DbTable_Lieu();

    	$request=$this->getRequest();
    	if ($request->isPost()) {

    		if($_FILES['image']['size']>0) {
    			$tempFile = $_FILES['image']['tmp_name'];
    			$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    			$targetPath = "photos/".$image;
                $recadrer=$this->getParam('recadrer',0);
                $recadrer=($recadrer==1)?true:false;
    			FNC::copyImg($_FILES['image']['tmp_name'],550,440,$targetPath,$recadrer);
    		} else {
    			$image="";
    		}

            $data = array(
                "libelle"=>$this->getParam("titre",''),
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
                "prix_11"=>$this->getParam("prix_11",0),
                "prix_12"=>$this->getParam("prix_12",0),
                "prix_13"=>$this->getParam("prix_13",0),
                "prix_14"=>$this->getParam("prix_14",0),
                "prix_14_plus"=>$this->getParam("prix_14_plus",0),
                "prix_public"=>$this->getParam("prix_public",0),
            );
            
            $tarif_id=$tbl_tarif_transfert->insert($data);

    		$data = array(
    				"titre"=>$this->getParam("titre",''),
                    "titre_en"=>$this->getParam("titre_en",''),
    				"libelle"=>$this->getParam("libelle",''),
    				"description"=>FNC::cleanHtmlTags($this->getParam("description",'')),
                    "duree"=>$this->getParam("duree",''),
                    "vehicule_id"=>$this->getParam("vehicule",0),
    				"image"=>$image,
    				"tarif_id"=>$tarif_id,
    				"destination_depart_id"=>$this->getParam("destination_depart_id",0),
    				"destination_arrivee_id"=>$this->getParam("destination_arrivee_id",0),
                    "type_id"=>"1",
                    "lieu_arrivee_id"=>$this->getParam("lieu_arrivee_id",0),
                    "lieu_depart_id" =>$this->getParam("lieu_depart_id",0),
                    "reduction_enfant" => $this->getParam("reduction_enfant",0),
                    "share_option"=>intval($this->getParam("share_option",0)),
    		);

    		$tbl_transfert->insert($data);
    		$this->_redirect('administration/transferts');
    		}
    		
    	$this->view->transferts=$tbl_transfert->fetchAll();
    	$this->view->destinations=$tbl_destination->fetchAll();
        $this->view->lieux = $tbl_lieu->fetchAll();
        $this->view->vehicule = $tbl_vehicule->fetchAll();
    	$this->view->ong=$this->getParam("ajouter",0);

        
        $this->view->tarifs=$tbl_tarif_transfert->fetchAll();
        FNC::writeDynamicRoutes();


        $dt_lieux = new Application_Model_DbTable_Lieu();
    }
    
    public function modifierAction()
    {
    	
    	
    	
    	$tbl_transfert= new Application_Model_DbTable_Transfert();
    	$tbl_vehicule= new Application_Model_DbTable_Vehicules();
        $tbl_destination=new Application_Model_DbTable_Destination();
    	$tbl_tarif_transfert=new Application_Model_DbTable_TarifTransfert();
        $tbl_lieu = new Application_Model_DbTable_Lieu();

    	 
    	$id=$this->getParam("id");
    	$transfert=$tbl_transfert->find($id);
    	if($transfert){
	    	$request=$this->getRequest();
	    	

	    	
	    	if ($request->isPost()) {
	    		if($request->getParam('subedit_main')){
		    		$image=$transfert->image;
		    		if($_FILES['image']['size']>0) {
		    			$tempFile = $_FILES['image']['tmp_name'];
		    			$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
		    			$targetPath = "photos/".$image;

                        $recadrer=$this->getParam('recadrer',0);
                        $recadrer=($recadrer==1)?true:false;
		    			FNC::copyImg($tempFile,550,440,$targetPath,$recadrer);
		    		}

                    $data = array(
                        "libelle"=>$this->getParam("titre",''),
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
                        "prix_11"=>$this->getParam("prix_11",0),
                        "prix_12"=>$this->getParam("prix_12",0),
                        "prix_13"=>$this->getParam("prix_13",0),
                        "prix_14"=>$this->getParam("prix_14",0),
                        "prix_14_plus"=>$this->getParam("prix_14_plus",0),
                        "prix_public"=>$this->getParam("prix_public",0),
                    );
                    $tarif_transfert=$tbl_tarif_transfert->find($transfert->tarif_id)->current();
                    if($tarif_transfert){
                        $tarif_id=$tarif_transfert->id;
                        $tbl_tarif_transfert->update($data,"id=".$tarif_transfert->id);
                    }else{
                        $tarif_id=$tbl_tarif_transfert->insert($data);
                    }
                    
		    		$data = array(
		    				"titre"=>$this->getParam("titre",''),
                            "titre_en"=>$this->getParam("titre_en",''),
		    				"libelle"=>$this->getParam("libelle",''),
                            "duree"=>$this->getParam("duree",''),
                            "vehicule_id"=>$this->getParam("vehicule",0),
		    				"description"=>FNC::cleanHtmlTags($this->getParam("description",'')),
		    				"image"=>$image,
		    				//"prix"=>$this->getParam("prix",''),
		    				"destination_depart_id"=>$this->getParam("destination_depart_id",0),
                            "destination_arrivee_id"=>$this->getParam("destination_arrivee_id",0),
		    				"tarif_id"=>$tarif_id,
                            "type_id"=>$this->getParam("type-transfer",0),
                            "lieu_arrivee_id"=>$this->getParam("lieu_arrivee_id",0),
                            "lieu_depart_id" =>$this->getParam("lieu_depart_id",0),
                            "reduction_enfant" => $this->getParam("reduction_enfant",0),
                        "share_option"=>intval($this->getParam("share_option",0)),
		    				 
		    		);
		    		
		    		$tbl_transfert->update($data,array("id=?"=>$transfert->id));





		    		
		    		$this->_redirect('administration/transferts/modifier/id/'.$transfert->id);
	    		}



				if($request->getParam('subedit_conditions')){
	    			$data = array(
		    				"conditions"=>FNC::cleanHtmlTags($this->getParam("conditions",'')),
                            "desc_group"=>$this->getParam("desc_group",""),
                            "desc_prive"=>$this->getParam("desc_prive","")

		    		);
		    		$tbl_transfert->update($data,array("id=?"=>$transfert->id));
	    			$this->_redirect('administration/transferts/modifier/id/'.$transfert->id.'/ong/conditions');
	    		}

	    		
				


	    		
	    		
	    	}
	    	
	    	
	    	
	    	
	    	$this->view->ong=$this->getParam('ong',null);
	    	

	    	$tarif_article=$tbl_tarif_transfert->find($transfert->tarif_id)->current();

            $this->view->tarif_article=$tarif_article;
	    	$this->view->transfert=$transfert;
	    	$this->view->destinations=$tbl_destination->fetchAll();
	    	
	    	
            $this->view->transferts=$tbl_transfert->fetchAll();
	    	$this->view->tarifs=$tbl_tarif_transfert->fetchAll();
            $this->view->vehicule = $tbl_vehicule->fetchAll();
            $this->view->lieux = $tbl_lieu->fetchAll();
	    	
    	}else{
    		$this->redirect('/administration');
    	}
    }


    public function getDestinations(){

        $tbl_categorie=new Application_Model_DbTable_Destination();
        $categories=$tbl_categorie->fetchAll();
        $categories_arr=array();
        foreach ($categories as $key => $value) {
            $categories_arr[$value->id]=$value;
        }
        return $categories_arr;
    }
    public function getTarifsTransferts(){
        $tbl_tarif=new Application_Model_DbTable_TarifTransfert();
        $lignes=$tbl_tarif->fetchAll();
        $lignes_arr=array();
        foreach ($lignes as $key => $value) {
            $lignes_arr[$value->id]=$value;
        }
        return $lignes_arr;
    }
    
    
}



