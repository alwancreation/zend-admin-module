<?php

class Administration_IndexController extends Zend_Controller_Action
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
        
        $this->view->page_select="accueil";

		FNC::writeDynamicRoutes();

    }
	
    
    public function delphotoAction()
    {
    	$id=$this->getParam('id',0);
    	$tbl_photo=new Application_Model_DbTable_Photo();
    	$photo=$tbl_photo->find($id)->current();
    	if($photo){
    		$photo->delete();
    	}
    	
    	$this->_redirect('administration/index/index/ong/photos');
    	
    }
    public function indexAction()
    {
    	
    	$tbl_page=new Application_Model_DbTable_Page();
    	$tbl_photo=new Application_Model_DbTable_Photo();
    	$tbl_slider = new Application_Model_DbTable_Slider();


    	$id=1;
    	$page=$tbl_page->find($id)->current();
    	if($page){

	    	$request=$this->getRequest();
	    	
	    	if ($request->isPost()) {


	    		if($request->getParam('titre')){
	    			$img_name=$page->image;
	    			if($_FILES['image']['size']>0){
	    				$img_name="img-".time().".jpg";
	    				$targetPath = "photos/".$img_name;
	    				FNC::copyImg($_FILES['image']['tmp_name'], 480, 390, $targetPath);
	    			}
	    			
	    			
		    		$data = array(
		    				"titre"=>$this->getParam("titre",''),
		    				"soustitre"=>$this->getParam("soustitre",''),
		    				"minidescription"=>FNC::cleanHtmlTags($this->getParam("minidescription",'')),
		    				"description"=>FNC::cleanHtmlTags($this->getParam("description",'')),
		    				"description2"=>FNC::cleanHtmlTags($this->getParam("description2",'')),

		    				"titre_en"=>$this->getParam("titre_en",''),
		    				"soustitre_en"=>$this->getParam("soustitre_en",''),
		    				"minidescription_en"=>FNC::cleanHtmlTags($this->getParam("minidescription_en",'')),
		    				"description_en"=>FNC::cleanHtmlTags($this->getParam("description_en",'')),
		    				"description2_en"=>FNC::cleanHtmlTags($this->getParam("description2_en",'')),

		    				"visible"=>$this->getParam("visible",1),
		    				"image"=>$img_name,
		    				"lien"=>$this->getParam("lien",""),
		    		);

		    		
		    		$tbl_page->update($data,array("id=?"=>$id));
		    		$this->_redirect('administration/index');

	    		}


	    		// add slide photo
	            if ($request->getParam('subaddphoto')) {

	                if ($_FILES['image']['size'] > 0) {

	                    $image = FNC::UploadImg('image', 'photos/home-slider', 1680, 500);

	                    $data = array(

	                        "src" => $image,
	                        "title" => $this->getParam("title", ""),
	                        "img_alt" =>  $this->getParam("img_alt", ""),
	                        "ordre"=> $this->getParam("ordre", 0)
	                    );

	                    $tbl_slider->insert($data);
	                }

	            }


	            // Update slide
	            if ($request->getParam('update_slides')) {


	                // rows
	                foreach ($_POST as $key => $value) {

	                    if(strpos($key, '-')) {


	                        $pieces = explode("-", $key);
	                        $row_id = $pieces[0];
	                        $col = $pieces[1];


	                        if(is_numeric($row_id)){


	                            $data = array(
	                                $col => $value ,
	                            );


	                            $tbl_slider->update($data, "id=$row_id");
	                            
	                        }
	                        
	                    }

	                }

	            }

	    		
	    	}


	    	 

			


	    	
	    	$this->view->ong=$this->getParam('ong',null);
	    	$this->view->article=$page;	
	    	// $this->view->photos=$tbl_photo->fetchAll("page_id=".$id);
	    	$this->view->photos = $tbl_slider->fetchAll(null, "ordre");
	    	
	    	
	    	
    	}else{

    		$this->redirect('/administration');

    	}

    }

    public function supprimersliderAction() {

        $tbl_slide = new Application_Model_DbTable_Slider();
        $idslide = $this->getParam("id", 0);
        $slide = $tbl_slide->find($idslide)->current();

        if ($slide) {
        	
        	unlink("photos/home-slider/". $slide->src) ;
            $slide->delete();
            $this->redirect("/administration");
        }

        exit();
    }
    
    
}



