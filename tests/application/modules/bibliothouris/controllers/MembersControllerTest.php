<?php

class Bibliothouris_MembersControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
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

    public function testRoutingMembersControllerOnIndexAction(){
        $url = '/bibliothouris/members/index/';
        $this->dispatch($url);

        $this->assertModule('bibliothouris');
        $this->assertController('members');
        $this->assertAction('list');
    }

    public function testRoutingMembersControllerOnIndexActionIfActionNotSet(){
        $url = '/bibliothouris/members/';
        $this->dispatch($url);

        $this->assertModule('bibliothouris');
        $this->assertController('members');
        $this->assertAction('list');
    }

    public function testRoutingMembersControllerOnLoginAction(){
        $url = '/bibliothouris/members/register/';
        $this->dispatch($url);

        $this->assertModule('bibliothouris');
        $this->assertController('members');
        $this->assertAction('register');
    }

    public function testIfLoginPageContainsLoginForm(){
        $url = '/bibliothouris/members/register/';
        $this->dispatch($url);

        $this->assertQueryCount('form#registerForm', 1);
    }

    public function testIfLoginPageContainsRightTitle(){
        $url = '/bibliothouris/members/register/';
        $this->dispatch($url);

        $this->assertQueryContentContains('div.page-title', 'Register a new member');
    }

    public function testIfAjaxInfoAreCorrectForListingMembers(){
        $url = '/bibliothouris/members/ajax-list-members/';
        $this->dispatch($url);


        $membersMapper = new Bibliothouris_Model_MembersMapper();
        $members = $membersMapper->fetchAll();
        $membersArray = array();
        foreach($members as $member) {
            $membersArray[] = $member->getFname() . ' ' . $member->getLname();
        }

        $httpResponse = $this->getResponse();

        $this->assertEquals($httpResponse->getBody(),Zend_Json::encode($membersArray));
        $this->assertHeaderContains('Content-Type', 'application/json');
        $this->assertResponseCode(200, null);
    }
}



