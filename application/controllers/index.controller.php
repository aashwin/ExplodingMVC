<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: index.controller.php
 * Date: 29/10/14
 * Time: 20:40
 */

class indexController extends BaseController
{
    public $userModel=null;
    public function __construct(){
        parent::__construct();
        $this->userModel=$this->loadModel("user");
    }
    public function index()
    {
        $this->addViewArray('eventsModel', $this->loadModel('events'));
        $likedEvents=$this->getViewArray('eventsModel')->likedEvents($this->userModel->loggedInUserData('userId'), 2);
        $upcomingData=$this->getViewArray('eventsModel')->getEvents(0, 8,'startTime', 'ASC', '', '', false, false, $additionalWhere="startTime>'".date(DB_DATETIME_FORMAT)."'");
        $this->addViewArray('upcomingData', $upcomingData);
        $this->addViewArray('likedData', $likedEvents);
        $this->title("Cricket Events");
        $this->setTemplateLayout('default');
        $this->loadView('Index', 'Index');
    }
    public function Search(){
        if(($_POST['q']=='') && $_POST['where']=='' && $_POST['fromDate']=='' && $_POST['toDate']==''){
            header("Location: ".Functions::pageLink());
            exit;
        }
        $this->addViewArray('eventsModel', $this->loadModel('events'));
        $eventSearch=$this->getViewArray('eventsModel')->search($_POST['q'], $_POST['where'], $_POST['fromDate'], $_POST['toDate']);
        $this->addViewArray('searchData', $eventSearch);
        $this->title("Search Results");
        $this->setTemplateLayout('default');
        $this->loadView('Index', 'Search');


    }
}