<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: routers.config.php
 * Date: 30/12/14
 * Time: 17:10
 */

$_ROUTERS=array();
$_ROUTERS[]=array('calender/tournament-([0-9]+)\.ics', 'index/EventsICal', array('([0-9]+)'));
$_ROUTERS[]=array('match/([0-9]+)-([a-zA-Z0-9-_]+)\.html', 'events/view', array('([0-9]+)','([a-zA-Z0-9-_]+)'));

?>