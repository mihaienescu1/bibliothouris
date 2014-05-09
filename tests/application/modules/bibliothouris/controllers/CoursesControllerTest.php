<?php

class Bibliothouris_CoursesControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function testRoutingBibliothourisModuleOnDefaultModule_ErrorController_ErrorIndex_IfControllerNotSet(){
        $url = '/bibliothouris/';
        $this->dispatch($url);
        $this->assertRedirect('/bibliothouris/courses/list');
    }

    public function testRoutingInexistingUriOnDefaultModule_ErrorController_ErrorIndex(){
        $url = '/inexistent/';
        $this->dispatch($url);

        $this->assertModule('default');
        $this->assertController('error');
        $this->assertAction('error');
    }

    public function testRoutingCoursesControllerOnIndexAction(){
        $url = '/bibliothouris/courses/index/';
        $this->dispatch($url);

        $this->assertModule('bibliothouris');
        $this->assertController('courses');
        $this->assertAction('list');
    }

    public function testRoutingCoursesControllerOnIndexActionIfActionNotSet(){
        $url = '/bibliothouris/courses/';
        $this->dispatch($url);

        $this->assertModule('bibliothouris');
        $this->assertController('courses');
        $this->assertAction('list');
    }

    public function testRoutingCoursesControllerOnLoginAction(){
        $url = '/bibliothouris/courses/register/';
        $this->dispatch($url);

        $this->assertModule('bibliothouris');
        $this->assertController('courses');
        $this->assertAction('register');
    }

    public function testIfLoginPageContainsLoginForm(){
        $url = '/bibliothouris/courses/register/';
        $this->dispatch($url);

        $this->assertQueryCount('form#registerForm', 1);
    }

    public function testIfLoginPageContainsRightTitle(){
        $url = '/bibliothouris/courses/register/';
        $this->dispatch($url);

        $this->assertQueryContentContains('div.page-title', 'Register a new course');
    }

    public function testIfAjaxInfoAreCorrectForListingCourses(){
        $url = '/bibliothouris/courses/ajax-list-courses/';
        $this->dispatch($url);


        $coursesMapper = new Bibliothouris_Model_CoursesMapper();
        $courses = $coursesMapper->fetchAll();
        $coursesArray = array();
        foreach($courses as $course) {
            $coursesArray[] = $course->getFname() . ' ' . $course->getLname();
        }

        $httpResponse = $this->getResponse();

        $this->assertEquals($httpResponse->getBody(),Zend_Json::encode($coursesArray));
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(200, null);
    }
}



