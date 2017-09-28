<?php



class Administration_ProduitsController extends Zend_Controller_Action

{



    public function init()

    {

    	$admin_user = new Zend_Session_Namespace('admin_user');
        if(!isset($admin_user->user)){
            $this->_redirect('administration/login');
        }

        

        /* Initialize action controller here */

        

        $layout = Zend_Layout::getMvcInstance();

        $layout->setLayoutPath(APPLICATION_PATH."/layouts/scripts/admin/");

        

        $this->view->page_select="produits";

    }

public function supprimerjourAction()

    {

    	$tbl_jour=new Application_Model_DbTable_Jour();

    	$idjour=$this->getParam("id",0);

    	$jour=$tbl_jour->find($idjour)->current();

    	if($jour){

    		$id_article=$jour->article_id;

    		$jour->delete();

    		$this->redirect("/administration/produits/modifier/id/".$id_article.'/ong/jours');

    	}

    	exit();

    }

    

    public function supprimerAction()

    {

    	$tbl_article=new Application_Model_DbTable_Article();

    	$id=$this->getParam("id",0);

    	$article=$tbl_article->find($id);

    	if($article){


            $tbl_article->delete(array("id=?"=>$article->id));

    		$this->redirect("/administration/produits");

    	}

    	exit();

    }

    

    public function indexAction()

    {

    	$tbl_categorie=new Application_Model_DbTable_Categorie();

    	$tbl_article= new Application_Model_DbTable_Article();

    	$tbl_tarif= new Application_Model_DbTable_Tarif();

    	$request=$this->getRequest();

    	if ($request->isPost()) {



    		if($_FILES['image']['size']>0) {

    			$tempFile = $_FILES['image']['tmp_name'];

    			$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

    			$targetPath = "photos/".$image;

    			$recadrer=$this->getParam("recadrer",0);

    			$recadrer=($recadrer==1)?true:false;

    			FNC::copyImg($_FILES['image']['tmp_name'],550,440,$targetPath,$recadrer);

    		} else {

    			$image="";

    		}



            $data = array(

                "libelle"=>$this->getParam('titre',''),

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
                "reduction_enfant" => $this->getParam("reduction_enfant",0),
            );

            $tarif_id=$tbl_tarif->insert($data);

    		

    		$data = array(

    				"categorie_id"=>$this->getParam("categorie_id",0),

    				"titre"=>$this->getParam("titre",''),

    				"soustitre"=>$this->getParam("soustitre",''),

    				"titre_en"=>$this->getParam("titre_en",''),

    				"soustitre_en"=>$this->getParam("soustitre_en",''),

    				"minidescription"=>FNC::cleanHtmlTags($this->getParam("minidescription",'')),

    				"minidescription_en"=>FNC::cleanHtmlTags($this->getParam("minidescription_en",'')),


    				"description"=>FNC::cleanHtmlTags($this->getParam("description",'')),

    				"description_en"=>FNC::cleanHtmlTags($this->getParam("description_en",'')),

    				

    				"image"=>$image,

    				"tarif_id"=>$tarif_id,

    				"visible"=>$this->getParam("visible",1),
    				"share_option"=>intval($this->getParam("share_option",0)),
    				"home_section"=>intval($this->getParam("home_section",0)),

    				"reduction_enfant" => $this->getParam("reduction_enfant",0),
					"url" => FNC::safeUrl( $this->getParam("titre",'') ).".html",

    	

    		);

    	

    	

    		$tbl_article->insert($data);

    		$this->_redirect('administration/produits');

    		}

    		

    	$this->view->categories=$tbl_categorie->fetchAll();

    	$this->view->tarifs=$tbl_tarif->fetchAll();

    	$this->view->articles=$tbl_article->fetchAll();

    	$this->view->ong=$this->getParam("ajouter",0);

    	FNC::writeDynamicRoutes();

    }

    

    public function modifierAction()

    {

    	

    	

    	

    	$tbl_categorie=new Application_Model_DbTable_Categorie();

    	$tbl_article= new Application_Model_DbTable_Article();

    	$tbl_photo= new Application_Model_DbTable_Photo();

    	$tbl_jour= new Application_Model_DbTable_Jour();

    	$tbl_localisation=new Application_Model_DbTable_ArticleLocalisation();

    	

    	$tbl_destination=new Application_Model_DbTable_Destination();

    	$tbl_theme=new Application_Model_DbTable_Theme();

    	$tbl_article_destination=new Application_Model_DbTable_ArticleDestination();

    	$tbl_article_theme=new Application_Model_DbTable_ArticleTheme();

    	$tbl_tarif= new Application_Model_DbTable_Tarif();

    	$id=$this->getParam("id");

    	$article=$tbl_article->find($id);

    	if($article){

	    	$request=$this->getRequest();

	    	

	    	

	    	$id_jour=$this->getParam("modifierjour",0);

	    	if($id_jour>0){

	    		$jour=$tbl_jour->find($id_jour)->current();

	    		if($jour){

	    			

	    			

	    			$this->view->jour=$jour;

	    		}

	    	}

	    	

	    	

	    	

	    	if ($request->isPost()) {

	    		if($request->getParam('titre')){

		    		$image=$article->image;

		    		if($_FILES['image']['size']>0) {

		    			$tempFile = $_FILES['image']['tmp_name'];

		    			$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

		    			$targetPath = "photos/".$image;

		    			$recadrer=$this->getParam('recadrer',0);

		    			$recadrer=($recadrer==1)?true:false;

		    			

		    			FNC::copyImg($tempFile,550,440,$targetPath,$recadrer);

		    		}

		    		

		    		

		    		$data = array(

		    				"categorie_id"=>$this->getParam("categorie_id",0),

		    				"titre"=>$this->getParam("titre",''),

		    				"code"=>$this->getParam("code",''),

		    				"jour_depart"=>$this->getParam("jour_depart",0),

		    				"prochain_depart"=>FNC::dateFrToMysql($this->getParam("prochain_depart",'')),

		    				"soustitre"=>$this->getParam("soustitre",''),

		    				"minidescription"=>FNC::cleanHtmlTags($this->getParam("minidescription",'')),

		    				"description"=>FNC::cleanHtmlTags($this->getParam("description",'')),

		    				"desc_transferts" =>$this->getParam("desc_transferts"),





		    				"titre_en"=>$this->getParam("titre_en",''),

		    				"soustitre_en"=>$this->getParam("soustitre_en",''),

		    				"minidescription_en"=>FNC::cleanHtmlTags($this->getParam("minidescription_en",'')),

		    				"description_en"=>FNC::cleanHtmlTags($this->getParam("description_en",'')),

		    				"desc_transferts_en" =>$this->getParam("desc_transferts_en"),



		    				"image"=>$image,

		    				//"tarif_id"=>$this->getParam("tarif_id",0),

		    				"visible"=>$this->getParam("visible",1),

		    				"depart_dimanche"=>$this->getParam("depart_dimanche",0),

							"depart_lundi"=>$this->getParam("depart_lundi",0),

							"depart_mardi"=>$this->getParam("depart_mardi",0),

							"depart_mercredi"=>$this->getParam("depart_mercredi",0),

							"depart_jeudi"=>$this->getParam("depart_jeudi",0),

							"depart_vendredi"=>$this->getParam("depart_vendredi",0),

							"depart_samedi"=>$this->getParam("depart_samedi",0),

							"reduction_enfant" => $this->getParam("reduction_enfant",0),
                            "share_option"=>intval($this->getParam("share_option",0)),
                            "home_section"=>intval($this->getParam("home_section",0)),
		    		);

		    		

		    		$tbl_article->update($data,array("id=?"=>$article->id));

		    		$hasTarif=false;

		    		if($article->tarif_id!=0){

		    			$tarif_article=$tbl_tarif->find($article->tarif_id)->current();

		    			if($tarif_article){

		    				$hasTarif=true;

		    				$data = array(

		                        "libelle"=>$article->titre,

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
                                "reduction_enfant" => $this->getParam("reduction_enfant",0),
		                    );

		                    $tbl_tarif->update($data,array("id=?"=>$tarif_article->id));

		    			}

		    		}

		    		if(!$hasTarif){

                        $data = array(

                            "libelle"=>$article->titre,

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
                            "reduction_enfant" => $this->getParam("reduction_enfant",0),
                        );

	                    $tarif_id=$tbl_tarif->insert($data);

	                    $tbl_article->update(array("tarif_id"=>$tarif_id),"id=".$article->id);

		    		}



		    		// modifications destinations 

		    		$tbl_article_destination->delete("article_id=".$article->id);

		    		$destinations_s=$this->getParam("destinations",array());

		    		foreach ($destinations_s as $id_dest){

		    			$tbl_article_destination->insert(array(

		    					"article_id"=>$article->id,

		    					"destination_id"=>$id_dest

		    			));

		    		}





		    		// modifications themes 

		    		$tbl_article_theme->delete("article_id=".$article->id);

		    		$themes_s=$this->getParam("themes",array());

		    		foreach ($themes_s as $id_dest){

		    			$tbl_article_theme->insert(array(

		    					"article_id"=>$article->id,

		    					"theme_id"=>$id_dest

		    			));

		    		}









		    		

		    		$this->_redirect('administration/produits/modifier/id/'.$article->id);

	    		}







	    		if($request->getParam('subedit_quand')){

	    			

	    			$data = array(

		    				"quand"=>FNC::cleanHtmlTags($this->getParam("quand",'')),
		    				"quand_prive"=> $this->getParam("quand_prive",'') ,
							"quand_group"=> $this->getParam("quand_group",'') ,
							"quand_dure"=> $this->getParam("quand_dure",'') ,
							"quand_lieu"=> $this->getParam("quand_lieu",'') ,
							"langues"=> $this->getParam("langues",'') ,
							"langues_en"=> $this->getParam("langues",'') ,
		    				"quand_en"=>FNC::cleanHtmlTags($this->getParam("quand_en",'')),
		    				"quand_prive_en"=> $this->getParam("quand_prive_en",'') ,
							"quand_group_en"=> $this->getParam("quand_group_en",'') ,
							"quand_dure_en"=> $this->getParam("quand_dure_en",'') ,
							"quand_lieu_en"=> $this->getParam("quand_lieu_en",'') ,

		    		);

		    		$tbl_article->update($data,array("id=?"=>$article->id));

	    			$this->_redirect('administration/produits/modifier/id/'.$article->id.'/ong/quand');

	    		}

	    		



				if($request->getParam('subedit_conditions')){

	    			$data = array(

		    				"conditions"=>FNC::cleanHtmlTags($this->getParam("conditions",'')),

		    				"conditions_en"=>FNC::cleanHtmlTags($this->getParam("conditions_en",'')),

		    				"desc_group"=>$this->getParam("desc_group",""),

                            "desc_prive"=>$this->getParam("desc_prive",""),

                            "desc_group_en"=>$this->getParam("desc_group_en",""),

                            "desc_prive_en"=>$this->getParam("desc_prive_en","")



		    		);

		    		$tbl_article->update($data,array("id=?"=>$article->id));

	    			$this->_redirect('administration/produits/modifier/id/'.$article->id.'/ong/conditions');

	    		}



	    		

				





	    		if($request->getParam('subaddjour')){

	    			

	    			$image="";

		    		if($_FILES['image']['size']>0) {

		    			$tempFile = $_FILES['image']['tmp_name'];

		    			$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

		    			$targetPath = "photos/".$image;

		    			move_uploaded_file($tempFile,$targetPath);

		    		}

	    			

	    			$data = array(

	    					"libelle"=>$this->getParam("libelle",''),

	    					"description"=>FNC::cleanHtmlTags($this->getParam("description",'')),

	    					

	    					"libelle_en"=>$this->getParam("libelle_en",''),

	    					"description_en"=>FNC::cleanHtmlTags($this->getParam("description_en",'')),



	    					"image"=>$image,

	    					"article_id"=>$article->id,

	    			);

	    			

	    			$tbl_jour->insert($data);

	    			

	    			$this->_redirect('administration/produits/modifier/id/'.$article->id.'/ong/jours');

	    		}

	    		

	    		if($request->getParam('subeditjour')){

	    			

	    			

	    			$image=$jour->image;

		    		if($_FILES['image']['size']>0) {

		    			$tempFile = $_FILES['image']['tmp_name'];

		    			$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

		    			$targetPath = "photos/".$image;

		    			move_uploaded_file($tempFile,$targetPath);

		    		}

	    			

	    			$data = array(

	    					"libelle"=>$this->getParam("libelle",''),

	    					"description"=>FNC::cleanHtmlTags($this->getParam("description",'')),

	    					"libelle_en"=>$this->getParam("libelle_en",''),

	    					"description_en"=>FNC::cleanHtmlTags($this->getParam("description_en",'')),



	    					"image"=>$image,

	    			);

	    			

	    			$tbl_jour->update($data,array("id=?"=>$jour->id));

	    			

	    			$this->_redirect('administration/produits/modifier/id/'.$article->id.'/ong/jours');

	    			

	    		}

	    		

	    		

	    		if($request->getParam('subaddphoto')){

	    			if($_FILES['image']['size']>0) {

	    				$tempFile = $_FILES['image']['tmp_name'];

	    				$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

	    				$targetPath = "photos/".$image;

	    				$recadrer=($this->getParam("recadrer"))?true:false;

	    				FNC::copyImg($tempFile,1000,430,$targetPath,$recadrer);

	    				

	    				

		    			$data=array(

		    					"article_id"=>$article->id,

		    					"src"=>$image,

		    					"url"=>'',

		    					"alt"=>''

		    			);

		    			$tbl_photo->insert($data);

	    			}

	    			$this->_redirect('administration/produits/modifier/id/'.$article->id.'/ong/photos');

	    		}

	    		

	    		if($request->getParam('editlocalisation')){

	    			$localisation=$tbl_localisation->fetchAll("article_id=".$article->id)->current();

	    			

	    			$hh_map_center=$this->getParam("hh_map_center");

	    			$hh_map_zoom=$this->getParam("hh_map_zoom");

	    			

	    			$hh_map_center=str_replace("(", "", $hh_map_center);

	    			$hh_map_center=str_replace(")", "", $hh_map_center);

	    			

	    			

	    			

	    			if($hh_map_center && $hh_map_zoom){

		    			if($localisation) {

		    				$arr_center=explode(",", $hh_map_center);

			    			$data=array(

			    					"article_id"=>$article->id,

			    					"h"=>trim($arr_center[0]),

			    					"v"=>trim($arr_center[1]),

			    					"z"=>$hh_map_zoom

			    			);

			    			$tbl_localisation->update($data, array("article_id=?"=>$article->id));

			    			

		    			}else{

		    				$arr_center=explode(",", $hh_map_center);

			    			$data=array(

			    					"article_id"=>$article->id,

			    					"h"=>trim($arr_center[0]),

			    					"v"=>trim($arr_center[1]),

			    					"z"=>$hh_map_zoom

			    			);

			    			$tbl_localisation->insert($data);

		    			}

	    			}

	    			$this->_redirect('administration/produits/modifier/id/'.$article->id.'/ong/localisation');

	    		}

	    		

	    		

	    	}

	    	$iddelphoto=$this->getParam("delphoto",0);

	    	if($iddelphoto>0){

		    	$photo=$tbl_photo->find($iddelphoto)->current();

		    	if($photo){

		    		$photo->delete();

		    		$this->_redirect('administration/produits/modifier/id/'.$article->id.'/ong/photos');

		    	}

	    	}

	    	

	    	

	    	

	    	$this->view->ong=$this->getParam('ong',null);

	    	

	    	$this->view->destinations_article=$tbl_article_destination->fetchAll("article_id=".$article->id);

	    	$this->view->themes_article=$tbl_article_theme->fetchAll("article_id=".$article->id);



	    	$this->view->localisation=$tbl_localisation->fetchAll("article_id=".$article->id)->current();

	    	

	    	$this->view->article=$article;

	    	$this->view->tarif_article=$tbl_tarif->find($article->tarif_id)->current();

	    	$this->view->photos=$tbl_photo->fetchAll("article_id=".$id);

	    	$this->view->categories=$tbl_categorie->fetchAll();

	    	$this->view->destinations=$tbl_destination->fetchAll();

	    	$this->view->themes=$tbl_theme->fetchAll();

	    	

	    	$this->view->jours=$tbl_jour->fetchAll("article_id=".$id);

	    	

	    	$this->view->articles=$tbl_article->fetchAll();

	    	

	    	

    	}else{

    		$this->redirect('/administration');

    	}

    }

    

    

}







