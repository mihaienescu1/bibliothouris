<?php
class Bibliothouris_JobsController extends Zend_Controller_Action {

    public function init() {
        if(!in_array(php_sapi_name(),array('cli','cgi'))){
            echo 'The job has to be run only from cli,cgi';
            die;
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }

    public function preDispatch() {
    }

    public function sendFeedbackMailsAction() {
		echo 'Job running';
    }

    public function postDispatch() {
    }
}
