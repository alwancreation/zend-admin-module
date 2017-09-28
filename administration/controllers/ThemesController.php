<?php



class Administration_ThemesController extends Zend_Controller_Action

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

        

        $this->view->page_select="themes";

    }

	

    public function indexAction()

    {



    	$tbl_theme=new Application_Model_DbTable_Theme();



    	$themes=$tbl_theme->fetchAll();

    	$request=$this->getRequest();



    	if ($request->isPost()) {



    		if($_FILES['image']['size']>0) {

                $tempFile = $_FILES['image']['tmp_name'];

                $image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                $targetPath = "photos/".$image;

                FNC::copyImg($_FILES['image']['tmp_name'],500,380,$targetPath,true);

                

            } else {



                $image="";



            }



    		$data = array(

    				"libelle"=>$this->getParam("libelle",''),

    				"description"=>$this->getParam("description",''),

                    "libelle_en"=>$this->getParam("libelle_en",''),

                    "description_en"=>$this->getParam("description_en",''),

                    "image"=>$image

    		);

    		 

    		$tbl_theme->insert($data);

    		$this->_redirect('administration/themes');

    	}

    	

    	$this->view->themes=$themes;

    }

    

    	

    	

    public function supprimerAction()

    {

    	exit();

    	

    }

    public function modifierAction()

    {



    	$tbl_theme=new Application_Model_DbTable_Theme();

    	$id=$this->getParam("id");

    	

    	$theme=$tbl_theme->find($id)->current();

    	if($theme){

    		$request=$this->getRequest();

    	

    		if ($request->isPost()) {



    			    $image=$theme->image;

                    if($_FILES['image']['size']>0) {

                        $tempFile = $_FILES['image']['tmp_name'];

                        $image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                         $targetPath = "photos/".$image;

                        FNC::copyImg($_FILES['image']['tmp_name'],500,380,$targetPath,true);

                    }

    				$data = array(

    						"libelle"=>$this->getParam("libelle",''),

                            "description"=>$this->getParam("description",''),

                            "libelle_en"=>$this->getParam("libelle_en",''),

                            "description_en"=>$this->getParam("description_en",''),

    						"image"=>$image,

    				);

    	

    				$tbl_theme->update($data,array("id=?"=>$id));

    				$this->_redirect('administration/themes/modifier/id/'.$id);

    		}

    	

    		$this->view->theme=$theme;

    	

    	}else{

    		$this->redirect('/administration/themes');

    	}

    	

    	

    }

    

    

}







