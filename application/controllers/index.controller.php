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
    public function getTournaments($id, $page=1, $ajax='false'){
        $tournaments=$this->loadModel('tournaments');
        $this->addViewArray("tournamentData", $tournaments->getTournament($id));
        $this->addViewArray("eventsModel", $this->loadModel('events'));
        $this->addViewArray("ajax", $ajax);
        $this->addViewArray("currentPage", intval($page));
        if($this->getViewArray('tournamentData')===false){
            echo '<h1>Invalid Tournament!</h1>';
            exit;
        }
        $data=$this->getViewArray('tournamentData');
        $this->title($data['tournamentName'].' Events');
        if($ajax=='false'){
            $this->setTemplateLayout('default');
        }
        if($page<1)
            $page=1;
        $perPage=6;
        $this->addViewArray("perPage", $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray("totalItems", $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'eventId', 'DESC', 'tournamentId', $id,true,true)->fetchColumn());
        $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents($start, $perPage,'startTime', 'ASC', 'tournamentId', $id,true));
        $this->loadView('Index', 'events_tournament');

    }
    public function allTeams($page=1, $ajax='false'){
        $this->addViewArray("teamsModel", $this->loadModel('teams'));
        $this->addViewArray("ajax", $ajax);
        $this->addViewArray("currentPage", intval($page));

        $this->title('Cricket Teams');
        if($ajax=='false'){
            $this->setTemplateLayout('default');
        }
        if($page<1)
            $page=1;
        $perPage=12;
        $this->addViewArray("perPage", $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray("totalItems", $this->getViewArray('teamsModel')->getTeams(NULL, NULL,'teamId', 'DESC', '', '',false,true)->fetchColumn());
        $this->addViewArray('teamsData', $this->getViewArray('teamsModel')->getTeams($start, $perPage,'teamName', 'ASC', '', '',false));
        $this->loadView('Index', 'all_teams');

    }
    public function allFixtures( $page=1, $ajax='false'){
        $this->addViewArray("eventsModel", $this->loadModel('events'));
        $this->addViewArray("ajax", $ajax);
        $this->addViewArray("currentPage", intval($page));

        $this->title('Cricket Fixtures');
        if($ajax=='false'){
            $this->setTemplateLayout('default');
        }
        if($page<1)
            $page=1;
        $perPage=6;
        $this->addViewArray("perPage", $perPage);
        $start=($page-1)*$perPage;
        $this->addViewArray("totalItems", $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'eventId', 'DESC', '','',false,true, "startTime>'".date(DB_DATETIME_FORMAT, time())."'")->fetchColumn());
        $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents($start, $perPage,'startTime', 'ASC', '','',true, false, "startTime>'".date(DB_DATETIME_FORMAT, time())."'"));
        $this->loadView('Index', 'all_events');

    }
    public function allTournaments( ){
        $this->addViewArray("tournamentsModel", $this->loadModel('tournaments'));


        $this->title('Cricket Tournaments/Series');
        $this->setTemplateLayout('default');
        $this->addViewArray('tournamentsData', $this->getViewArray('tournamentsModel')->getSearchList());
        $this->loadView('Index', 'all_tournaments');

    }
    //EventsICAL Method is no longer used. Moved to icalController.
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