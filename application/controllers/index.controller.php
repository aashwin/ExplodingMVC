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
        $this->title("Cricket Events");
        $this->setTemplateLayout('default');
        $this->loadView('Index', 'Index');
    }




    //EventsICAL Method is no longer used. Moved to icalController. Deprecated
    public function EventsICal($id=0, $slug=''){
        $this->addViewArray("eventsModel", $this->loadModel('events'));

        if($id!=0){
            $tournaments=$this->loadModel('tournaments');
            $this->addViewArray("addressModel", $this->loadModel('address'));
            if($this->getViewArray('tournamentData')===false){
                echo '<h1>Invalid Tournament!</h1>';
                exit;
            }
            $data=$tournaments->getTournament($id);
            $filename=Functions::makeSlug($id.'-'.$data['tournamentName']);

            $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'startTime', 'ASC', 'tournamentId', $id,true, "startTime>'".date(DB_DATETIME_FORMAT, time())."'"));
        }else{
            $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'startTime', 'ASC', '','', false, false, "startTime>'".date(DB_DATETIME_FORMAT, time())."'"));
            $filename='all_cricket_fixtures';
        }
        define('DISPLAY_ICAL_DATE', 'Ymd\THis\Z');
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename='.$filename.'.ics');
        $this->loadView('Index', 'events_ical');

    }



}