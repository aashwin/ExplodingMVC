<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events.controller.php
 * Date: 30/12/14
 * Time: 17:10
 */

class eventsController extends BaseController
{
    public $userModel=null;
    public function __construct(){
        parent::__construct();
        $this->userModel=$this->loadModel("user");
    }
    public function view($id, $slug)
    {
        $this->addViewArray('eventsModel',$this->loadModel('events'));
        $this->addViewArray('addressModel',$this->loadModel('address'));
        $this->addViewArray('teamsModel',$this->loadModel('teams'));
        $this->addViewArray("eventData", $this->getViewArray('eventsModel')->getEvent($id));
        if($this->getViewArray('eventData')===false){
            echo '<h1>Invalid Event!</h1>';
            exit;
        }
        $data=$this->getViewArray('eventData');
        $this->addViewArray('TeamOne', $this->getViewArray('teamsModel')->getTeam($data['teamOne']));
        $this->addViewArray('TeamTwo', $this->getViewArray('teamsModel')->getTeam($data['teamTwo']));
        $this->addViewArray('GroundAddress', $this->getViewArray('addressModel')->getAddress($data['addressId']));
        $this->setTemplateLayout('default');
        $teamOne=$this->getViewArray('TeamOne');
        $teamTwo=$this->getViewArray('TeamTwo');
        $name=$this->getViewArray('eventsModel')->buildName($data['eventName'], $teamOne['teamName'], $teamTwo['teamName']);
        $this->title($name .' ['.date('M Y', strtotime($data['startTime'])).']');
        $this->loadView('Events', 'events_view');
    }




}