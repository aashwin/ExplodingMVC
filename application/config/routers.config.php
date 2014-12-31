<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: routers.config.php
 * Date: 30/12/14
 * Time: 17:10
 */

$_ROUTERS=array();
$_ROUTERS[]=array('calender/tournament-([0-9]+)\.ics', 'ical/tournaments', array('([0-9]+)'));
$_ROUTERS[]=array('calender/all-events\.ics', 'ical/index', array());
$_ROUTERS[]=array('calender/team-([0-9]+)\.ics', 'ical/teams', array('([0-9]+)'));
$_ROUTERS[]=array('match/([0-9]+)-([a-zA-Z0-9-_]+)\.html', 'events/view', array('([0-9]+)','([a-zA-Z0-9-_]+)'));

?>