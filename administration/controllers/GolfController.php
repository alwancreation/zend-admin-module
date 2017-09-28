<?php

class Administration_GolfController extends Zend_Controller_Action
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
        
        $this->view->page_select="golf";
    }
	
    
    public function delphotoAction()
    {
    	$id=$this->getParam('id',0);
    	$tbl_photo=new Application_Model_DbTable_Photo();
    	$photo=$tbl_photo->find($id)->current();
    	if($photo){
    		$photo->delete();
    	}
    	
    	$this->_redirect('administration/golf/index/ong/photos');
    	
    }
    public function indexAction()
    {
    	
    	$tbl_page=new Application_Model_DbTable_Page();
    	$tbl_photo=new Application_Model_DbTable_Photo();
    	$id=14;
    	$page=$tbl_page->find($id)->current();
    	if($page){
	    	$request=$this->getRequest();
	    	
	    	if ($request->isPost()) {
	    		if($request->getParam('titre')){
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
		    		);
		    		
		    		$tbl_page->update($data,array("id=?"=>$id));
		    		$this->_redirect('administration/golf');
	    		}
	    		
	    		
	    		
	    		
	    		
	    		if($request->getParam('subaddphoto')){
	    			if($_FILES['image']['size']>0) {
	    				$tempFile = $_FILES['image']['tmp_name'];
	    				$image="P".strftime("%d%m%y%H%M%S").".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
	    				$targetPath = "photos/".$image;
	    				move_uploaded_file($tempFile,$targetPath);
	    				
		    			$data=array(
		    					"page_id"=>$id,
		    					"src"=>$image,
		    					"url"=>'',
		    					"alt"=>''
		    			);
		    			$tbl_photo->insert($data);
	    			}
	    			$this->_redirect('administration/golf/index/ong/photos');
	    		}
	    		
	    	}
	    	
	    	$this->view->ong=$this->getParam('ong',null);
	    	
	    	$this->view->article=$page;
	    	$this->view->photos=$tbl_photo->fetchAll("page_id=".$id);
	    	
	    	
	    	
    	}else{
    		$this->redirect('/administration');
    	}
    }
    
    
}



