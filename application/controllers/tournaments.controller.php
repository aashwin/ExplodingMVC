<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments.controller.php
 * Date: 1/1/14
 * Time: 16:10
 */

class tournamentsController extends BaseController
{
    public $userModel=null;
    public function __construct(){
        parent::__construct();
        $this->userModel=$this->loadModel("user");
    }
    public function all( ){
        $this->addViewArray("tournamentsModel", $this->loadModel('tournaments'));
        $this->title('Cricket Tournaments/Series');
        $this->setTemplateLayout('default');
        $this->addViewArray('tournamentsData', $this->getViewArray('tournamentsModel')->getSearchList());
        $this->loadView('Tournaments', 'all_tournaments');

    }
    public function view($id, $page=1, $order='startTime', $by='ASC', $ajax='false'){
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

        $this->addViewArray("order", $order);
        $this->addViewArray("orderBy", strtolower($by));

        $this->addViewArray("totalItems", $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'eventId', 'DESC', 'tournamentId', $id,true,true)->fetchColumn());
        $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents($start, $perPage,$order, $by, 'tournamentId', $id,true));
        $this->loadView('Tournaments', 'events_tournament');

    }



}