<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: ical.controller.php
 * Date: 31/12/14
 * Time: 16:42
 */

class icalController extends BaseController
{
    public function index(){
        $this->addViewArray("eventsModel", $this->loadModel('events'));

        $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'startTime', 'ASC', '','', false, false, "startTime>'".date(DB_DATETIME_FORMAT, time())."'"));
        $filename='all_cricket_fixtures';
        $this->setupIcal($filename);
        $this->loadView('ical', 'file');
    }
    public function tournaments($id, $slug=''){
        $this->addViewArray("eventsModel", $this->loadModel('events'));
        $tournaments=$this->loadModel('tournaments');
        $this->addViewArray("addressModel", $this->loadModel('address'));
        $data=$tournaments->getTournament($id);
        if($data===false){
            echo '<h1>Invalid Tournament!</h1>';
            exit;
        }
        $filename=Functions::makeSlug($id.'-'.$data['tournamentName']);

        $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'startTime', 'ASC', 'tournamentId', $id,true,false, "startTime>'".date(DB_DATETIME_FORMAT, time())."'"));

        $this->setupIcal($filename);
        $this->loadView('ical', 'file');
    }
    public function teams($id){
        $this->addViewArray("eventsModel", $this->loadModel('events'));
        $teams=$this->loadModel('teams');
        $this->addViewArray("addressModel", $this->loadModel('address'));
        $data=$teams->getTeam($id);
        if($data===false){
            echo '<h1>Invalid Team!</h1>';
            exit;
        }
        $filename=Functions::makeSlug($id.'-'.$data['teamName']);

        $this->addViewArray('eventsData', $this->getViewArray('eventsModel')->getEvents(NULL, NULL,'startTime', 'ASC', '', '',true,false, "(teamOne=$id OR teamTwo=$id) AND startTime>'".date(DB_DATETIME_FORMAT, time())."'"));
        $this->setupIcal($filename);
        $this->loadView('ical', 'file');
    }
    private function setupIcal($filename='calender'){
        define('DISPLAY_ICAL_DATE', 'Ymd\THis\Z');
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename='.$filename.'.ics');
    }



}