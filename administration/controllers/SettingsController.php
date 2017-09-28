<?php

class Administration_SettingsController extends Zend_Controller_Action
{

    public function init(){
    	$admin_user = new Zend_Session_Namespace('admin_user');
    	if(!Zend_Auth::getInstance()->hasIdentity() or !isset($admin_user->user))
    	{
    		$this->_redirect('administration/login');
    	}
    	 
    	$layout = Zend_Layout::getMvcInstance();
    	$layout->setLayoutPath(APPLICATION_PATH."/layouts/scripts/admin/");
    	 
    	$this->view->page_select='settings';
        
    }

    public function indexAction()
    {
    	$tbl_user = new Application_Model_DbTable_User();
    	$tbl_settings = new Application_Model_DbTable_Settings();
    	
    	
    	if($this->getRequest()->isPost()){
    		
    		$subcodes=$this->getParam("Enregistrer");
    		$subinfos=$this->getParam("subeditVentes");
    		$subsettings=$this->getParam("subeditsettings");

            $taux_change = (is_numeric($this->getParam("taux_change",0.9)) ? $this->getParam("taux_change",0.9) : 0.9) ;

            // (is_numeric($this->getParam("taux_change",0.9))) : (($month - 1) % 7 % 2 ? 30 : 31));  is_numeric
    		
    		if($subsettings){
    			$data = array(
    					"name" => $this->getParam("name",''),
    					"url" => $this->getParam("url",''),
    					"description" => $this->getParam("description",''),
    					"tel" => $this->getParam("tel",''),
    					"fax" => $this->getParam("fax",''),
    					"gsm" => $this->getParam("gsm",''),
                        "taux_change" => $taux_change,
    					"email" => $this->getParam("email",''),
    					"email2" => $this->getParam("email2",''),
    					"adresse" => $this->getParam("adresse",''),
    					"analytics" => $this->getParam("analytics",''),
    					"has_payment" => $this->getParam("has_payment",'0'),
    					"online" => $this->getParam("online",0),
    					"lat" => $this->getParam("lat"),
    					"lng" => $this->getParam("lng"),
		        );
		        $tbl_settings->update($data, 'id = 1');
		        $this->redirect('/administration/settings');
    		}
    		
    		if($subcodes){
    		
	    		$id=intval($this->getParam("id"));
	    		
	    		$email=$this->getParam("username");
	    		$pass=$this->getParam("password");
	    		$pass2=$this->getParam("confirmPassword");
	    		
	    		if(strlen($pass)>3 && strlen($email)>3 && $pass2==$pass){
	    			$data = array(
			            'username' => $this->getParam("username"),
			            'password' => sha1($this->getParam("password")),
			        );
			        $tbl_user->update($data, 'id = '. (int)$id);
			        
			        $this->_helper->redirector('index');
	    		}
		        
	    	}
    	}
    	
    	$this->view->config=$tbl_settings->find(1)->current();
        $this->view->user=$tbl_user->find(1)->current();
    }
    
    
    public function socialAction(){
    	 
    	$tbl_social=new Application_Model_DbTable_Social();
    	 
    	 
    	$request=$this->getRequest();
    	if ($request->isPost()) {
    
    		$data = array(
    				'visible' => $request->getParam("visible",0),
    				'facebook' => $request->getParam("facebook",''),
    				'twitter' => $request->getParam("twitter",''),
    				'google' => $request->getParam("google",''),
                    'youtube' => $request->getParam("youtube",''),
                    'instagram' => $request->getParam("instagram",''),
                    'pintrest' => $request->getParam("pintrest",''),
    		);
    
    		$tbl_social->update($data,array("id=?"=>1));
    		$this->redirect("/administration/settings/social");
    	}
    	 
    	$this->view->social=$tbl_social->find(1)->current();
    	 
    }
    
    
}

