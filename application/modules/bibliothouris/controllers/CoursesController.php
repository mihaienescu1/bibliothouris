<?php
class Bibliothouris_CoursesController extends Zend_Controller_Action {

    public function init() {
		$this->_helper->layout->setLayout('layout');
    }

    public function preDispatch() {
        /* Nothing to do right now */
    }

    public function postDispatch() {
        /* Nothing to do right now */
    }

    public function indexAction() {
        $this->_forward('list');
    }

    public function listAction() {
        $this->view->headTitle()->headTitle('Courses list Title');
    }

    public function registerAction() {
        $registerCourseForm = new Bibliothouris_Form_RegisterCourse();
        $registerCourseForm->setAction($this->getFrontController()->getBaseUrl() . '/bibliothouris/courses/register-save');

        if($this->getRequest()->isPost()){
            $registerCourseForm->populate($this->getRequest()->getPost());
        }

        $registerCourseForm->setPrevalidation();
        $this->view->registerCourseForm = $registerCourseForm;
    }

    public function registerSaveAction(){

        $this->view->headTitle()->headTitle('Register new course');

        $errMessages = array();
        
        $request = $this->getRequest();
        $form    = new Bibliothouris_Form_RegisterCourse();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {

                $coursesMapper = new Bibliothouris_Model_CoursesMapper();
                $coursesModel = $coursesMapper->loadModel($request->getPost());
                $coursesModel->getMapper()->getDbTable()->insert(
                    $coursesModel->getMapper()->toArray($coursesModel)
                );
            } else {
                $errMessages = $form->getMessages();
            }
        }

        foreach($errMessages as $k => $v) {
            $this->view->errorMessages = implode('<br />', $v);
        }

        if (empty($errMessages)) {
            $this->_redirect('bibliothouris/courses/index');
        } else {
            $this->_forward('register');
        }
    }

    public function ajaxListCoursesAction() {
        
        $coursesMapper = new Bibliothouris_Model_CoursesMapper();
        $courses = $coursesMapper->fetchAll();
        $coursesArray = array();
        foreach($courses as $course) {

            $coursesArray[] = array(
                $course->getDateStart(),
                $course->getDateEnd(),
                $course->getTitle(),
                $course->getTrainerName()
            );

        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        echo $this->_helper->json($coursesArray, false);

    }

}
