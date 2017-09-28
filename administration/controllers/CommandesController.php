<?php

class Administration_CommandesController extends Zend_Controller_Action
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
        
        $this->view->page_select="commandes";
    }

    
   
    
    public function indexAction()
    {
        $tbl_categorie=new Application_Model_DbTable_Categorie();
        $tbl_article= new Application_Model_DbTable_Article();
        $tbl_commande=new Application_Model_DbTable_Commande();
        $tbl_client=new Application_Model_DbTable_Client();
        
        $commades=$tbl_commande->fetchAll($tbl_commande->select()->where("etat=1 or etat=302 or etat=2")->order("id desc"));
        $arr_clients=array();
        foreach ($commades as $cmd){
            $arr_clients[$cmd->client_id]=$tbl_client->find($cmd->client_id)->current();
        }
        $this->view->clients=$arr_clients;
        $this->view->commandes=$commades;
        
    }

    public function detailsAction()
    {
    	$tbl_categorie=new Application_Model_DbTable_Categorie();
    	$tbl_article= new Application_Model_DbTable_Article();
        $tbl_commande=new Application_Model_DbTable_Commande();
    	$tbl_client=new Application_Model_DbTable_Client();
    	


        $id=$this->getParam('id',0);
        $commade=$tbl_commande->find($id)->current();
        if($commade){
            $this->view->my_commade=$commade;

            $select=new Zend_Db_Select(Zend_Db_Table::getDefaultAdapter());
            $select->from(array("art"=>"article"),"art.*")
                    ->from(array("cmdl"=>"commande_ligne"),array("cmdl.prix","cmdl.date_debut","cmdl.reservation_type","cmdl.nombre_adultes","cmdl.nombre_enfants","cmdl.nombre_bebe", "cmdl.prix_adulte", "cmdl.prix_enfant","cmdl.hotel_prise_charge", "cmdl.hotel_prise_charge","cmdl.num_vol","cmdl.num_vol", "cmdl.langue" ,"cmdl.remarque", "commande_ligne_id"=>"cmdl.id"))
                    ->from(array("cmd"=>"commande"),null)
                    ->where("cmdl.article_id=art.id")
                    ->where("cmdl.commande_id=cmd.id")
                    ->where("cmdl.article_id=art.id")
                    ->where("cmdl.article_type=1")
                    ->where("cmd.id=".$commade->id)
                    ->where("cmd.client_id=".$commade->client_id);
            $query=$select->query();
            $articles=$query->fetchAll(Zend_Db::FETCH_OBJ);
            
            $select_2=new Zend_Db_Select(Zend_Db_Table::getDefaultAdapter());
            $select_2->from(array("art"=>"transfert"),"art.*")
                    ->from(array("cmdl"=>"commande_ligne"),array("cmdl.prix","cmdl.date_debut","cmdl.date_fin","cmdl.heure_arrive","cmdl.heure_depart","cmdl.reservation_type","cmdl.nombre_adultes","cmdl.nombre_enfants","cmdl.nombre_bebe", "cmdl.prix_adulte", "cmdl.prix_enfant","cmdl.hotel_prise_charge", "cmdl.hotel_prise_charge","cmdl.num_vol","cmdl.num_vol", "cmdl.langue" ,"cmdl.remarque","commande_ligne_id"=>"cmdl.id"))
                    ->from(array("cmd"=>"commande"),null)
                    ->where("cmdl.article_id=art.id")
                    ->where("cmdl.commande_id=cmd.id")
                    ->where("cmdl.article_id=art.id")
                    ->where("cmdl.article_type=2")
                    ->where("cmd.id=".$commade->id)
                    ->where("cmd.client_id=".$commade->client_id);
            $query_2=$select_2->query();
            $transferts=$query_2->fetchAll(Zend_Db::FETCH_OBJ);


            $this->view->articles=$articles;
            $this->view->transferts=$transferts;

            $client=$tbl_client->find($commade->client_id)->current();
            $this->view->commande_client=$client;
            $this->view->tarifs=$this->getTarifs();

        }else{
            throw new Zend_Controller_Action_Exception("Error Processing Request", 404);
            
        }




    	$commades=$tbl_commande->fetchAll($tbl_commande->select()->where("etat=1")->order("id desc"));
    	$arr_clients=array();
    	foreach ($commades as $cmd){
    		$arr_clients[$cmd->client_id]=$tbl_client->find($cmd->client_id)->current();
    	}
    	$this->view->clients=$arr_clients;
    	$this->view->commandes=$commades;
    	
    }
        
    public function getCategories(){

        $tbl_categorie=new Application_Model_DbTable_Categorie();
        $categories=$tbl_categorie->fetchAll();
        $categories_arr=array();
        foreach ($categories as $key => $value) {
            $categories_arr[$value->id]=$value;
        }
        return $categories_arr;
    }

    public function getThemes(){

        $tbl_theme=new Application_Model_DbTable_Theme();
        $lignes=$tbl_theme->fetchAll();
        $lignes_arr=array();
        foreach ($lignes as $key => $value) {
            $lignes_arr[$value->id]=$value;
        }
        return $lignes_arr;
    }
    
    public function getTarifs(){
        $tbl_tarif=new Application_Model_DbTable_Tarif();
        $lignes=$tbl_tarif->fetchAll();
        $lignes_arr=array();
        foreach ($lignes as $key => $value) {
            $lignes_arr[$value->id]=$value;
        }
        return $lignes_arr;
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



