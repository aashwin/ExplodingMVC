<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: teams.controller.php
 * Date: 30/12/14
 * Time: 17:10
 */

class teamsController extends BaseController
{
    public $userModel=null;
    public function __construct(){
        parent::__construct();
        $this->userModel=$this->loadModel("user");
    }
    public function view($id, $slug,$page=1, $ajax='false')
    {
        $this->addViewArray('teamsModel',$this->loadModel('teams'));
        $this->addViewArray("teamsData", $this->getViewArray('teamsModel')->getTeam($id));
        if($this->getViewArray('teamsData')===false){
            header('Location: '.Functions::pageLink('Error','Error404'));
            exit;
        }
        $data=$this->getViewArray('teamsData');
        if($slug!=Functions::makeSlug($data['teamName'])){
            header('Location: '.Functions::pageLink('Error','Error404'));
            exit;
        }
        $this->addViewArray("eventsModel", $this->loadModel('events'));
        $this->addViewArray("ajax", $ajax);
        $this->addViewArray("currentPage", intval($page));
        if($ajax=='false'){
            $this->setTemplateLayout('default');
        }
        if($page<1)
            $page=1;
        $perPage=6;
        $this->addViewArray("perPage", $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray("totalItems", $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'eventId', 'DESC', '', '',false,true, "teamOne=$id OR teamTwo=$id")->fetchColumn());
        $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents($start, $perPage,'startTime', 'ASC', '', '',false,false, "teamOne=$id OR teamTwo=$id"));

        $this->title($data['teamName'].' Cricket Team');
        $this->loadView('Teams', 'view');
    }




}