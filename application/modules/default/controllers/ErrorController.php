<?php

class ErrorController extends Zend_Controller_Action {
   public function errorAction() {
   	    #$this->_helper->layout->disableLayout();
        $errors = $this->_getParam('error_handler');
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'Route is not available';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                #404 error - controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Route is not available';
                break;
            default:
				$this->getResponse()->setHttpResponseCode(401);
				$priority = Zend_Log::CRIT;
				$this->view->message = 'System error';
                break;
        }

        $this->view->priority = $priority;

        if (isset($errors->exception)) {
            $exception = $errors->exception;
            #here can be placed eventualy a logger
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;

        #conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->displayExceptions = true;
        }

        if (APPLICATION_ENV != 'production') {
            $this->view->debug = true;
        }

        if($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->setHeader('exceptionMessage', $this->view->message);
        }
    }
	
}
