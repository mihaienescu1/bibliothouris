<?php
class Bibliothouris_MembersController extends Zend_Controller_Action {

    public function init() {
		$this->_helper->layout->setLayout('layout');
    }

    public function preDispatch() {
        $this->view->baseUrl = $this->getFrontController()->getBaseUrl();
    }

    public function indexAction() {
		$this->_forward('list');
    }

    public function registerAction() {
        $registerMemberForm = new Bibliothouris_Form_RegisterMember();
        $registerMemberForm->setAction($this->getFrontController()->getBaseUrl() . '/bibliothouris/members/register-save');

        if($this->getRequest()->isPost()){
            $registerMemberForm->populate($this->getRequest()->getPost());
        }

        $registerMemberForm->setPrevalidation();
        $this->view->registerMemberForm = $registerMemberForm;
    }

    public function registerSaveAction() {
		$this->view->headTitle()->headTitle('Register new member');
        $errMessages = array();


        $request = $this->getRequest();
        $form    = new Bibliothouris_Form_RegisterMember();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

                $membersMapper = new Bibliothouris_Model_MembersMapper();
                $membersModel = $membersMapper->loadModel($request->getPost());
                $membersModel->setPassword(md5($membersModel->getPassword()));
                $membersModel->getMapper()->getDbTable()->insert(
                    $membersModel->getMapper()->toArray($membersModel)
                );
            } else {
                $errMessages = $form->getMessages();
            }
        }

        foreach($errMessages as $k => $v) {
            $this->view->errorMessages = implode('<br />', $v);
        }

        if (empty($errMessages)) {
            $this->_redirect('bibliothouris/members/index');
        } else {
            $this->_forward('register');
        }

    }

	public function listAction() {
        $this->view->headTitle()->headTitle('Members Listings');
    }

	public function ajaxListMembersAction() {

        $membersMapper = new Bibliothouris_Model_MembersMapper();
        $members = $membersMapper->fetchAll();
        $membersArray = array();
        foreach($members as $member) {
            $membersArray[] = array(
                $member->getFname() . ' ' . $member->getLname()
            );
        }

		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		echo $this->_helper->json($membersArray, false);

    }


    public function postDispatch() {

    }
}
