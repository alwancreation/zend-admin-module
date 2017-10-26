<?php

class Administration_PagesController extends Zend_Controller_Action
{

    public function init()
    {
        $admin_user = new Zend_Session_Namespace('admin_user');
        if (!Zend_Auth::getInstance()->hasIdentity() or !isset($admin_user->user)) {
            $this->_redirect('administration/login');
        }

        /* Initialize action controller here */

        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayoutPath(APPLICATION_PATH . "/layouts/scripts/admin/");

        FNC::writeDynamicRoutes();

        $this->view->page_select = "pages";
    }


    public function delphotoAction()
    {
        $id = $this->getParam('id', 0);
        $tbl_photo = new Application_Model_DbTable_Photo();
        $photo = $tbl_photo->find($id)->current();
        if ($photo) {
            $photo->delete();
        }

        $this->_redirect('administration/pages/index/ong/photos');

    }


    public function indexAction()
    {
        $tbl_page = new Application_Model_DbTable_Page();
        $tbl_photo = new Application_Model_DbTable_Photo();

        $pages = $tbl_page->fetchAll();
        $this->view->pages = $pages;


    }

    public function modifierAction()
    {

        $tbl_page = new Application_Model_DbTable_Page();

        $id = $this->getParam("id", 0);
        $page = $tbl_page->find($id)->current();
        $language = $this->getParam("language", "fr");
        if ($page) {
            $this->view->assign('language', $language);
            $this->view->assign('article', $page);
            return;
        }
        $this->redirect('/administration');
    }

    public function updateAction()
    {
        $tbl_page = new Application_Model_DbTable_Page();
        $id = $this->getParam("id", 0);
        $page = $tbl_page->find($id)->current();
        if ($page) {
            $language = $this->getParam("language");
            if ($this->getRequest()->isPost()) {
                switch ($language) {
                    case 'fr':
                        $data = array(
                            "titre" => $this->getParam("titre", ''),
                            "url" => $this->getParam("url", ''),
                            "soustitre" => $this->getParam("soustitre", ''),
                            "description" => FNC::cleanHtmlTags($this->getParam("description", '')),
                            "meta" => $this->getParam("meta", ''),
                        );
                        break;
                    case 'en':
                        $data = array(
                            "titre_en" => $this->getParam("titre", ''),
                            "url_en" => $this->getParam("url", ''),
                            "soustitre_en" => $this->getParam("soustitre", ''),
                            "description_en" => FNC::cleanHtmlTags($this->getParam("description", '')),
                            "meta_en" => $this->getParam("meta", ''),
                        );
                        break;
                    case 'es':
                        $data = array(
                            "titre_es" => $this->getParam("titre", ''),
                            "url_es" => $this->getParam("url", ''),
                            "soustitre_es" => $this->getParam("soustitre", ''),
                            "description_es" => FNC::cleanHtmlTags($this->getParam("description", '')),
                            "meta_es" => $this->getParam("meta", ''),
                        );
                        break;
                    default:
                        break;
                }

                $tbl_page->update($data, array("id=?" => $id));
                $this->redirect("/administration/pages/modifier/id/" . $id . "/language/" . $language);
            }
        }
        $this->redirect("/administration/pages");
    }



}



