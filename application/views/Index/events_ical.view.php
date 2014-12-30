<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events_ical.view.php
 * Date: 29/12/14
 * Time: 23:05
 */?>
BEGIN:VCALENDAR<?php echo PHP_EOL;?>
PRODID:-//Aashwin Mohan//Cricket Events//EN<?php echo PHP_EOL;?>
VERSION:2.0<?php echo PHP_EOL;?>
METHOD:PUBLISH<?php echo PHP_EOL;?>
<?php
if($this->getViewArray('eventsData')!==false){
    $teamModel=$this->loadModel('teams');
    while($r=$this->getViewArray('eventsData')->fetch()){
        $teamOne=$teamModel->getTeam($r['teamOne']);
        $teamTwo=$teamModel->getTeam($r['teamTwo']);
        $address=$this->getViewArray('addressModel')->getAddress($r['addressId']);?>
BEGIN:VEVENT<?php echo PHP_EOL;?>
DESCRIPTION:Clash between <?php echo $teamOne['teamName'].' and '.$teamTwo['teamName'].'. For more details check: '.Functions::pageLink('Events', 'view', $r['eventId']).PHP_EOL;?>
SUMMARY:<?php echo $this->getViewArray('eventsModel')->buildName($r['eventName'], $teamOne['teamName'],$teamTwo['teamName']).PHP_EOL;?>
UID:<?php echo $r['eventId'].PHP_EOL;?>
URL:<?php echo Functions::pageLink('Events', 'view', $r['eventId'],$this->getViewArray('eventsModel')->buildName($r['eventName'], $teamOne['teamName'],$teamTwo['teamName'])).PHP_EOL;?>
STATUS:CONFIRMED<?php echo PHP_EOL;?>
DTSTART:<?php echo date(DISPLAY_ICAL_DATE, strtotime($r['startTime'])).PHP_EOL;?>
DTEND:<?php echo date(DISPLAY_ICAL_DATE, strtotime($r['startTime'])+(3600*3)).PHP_EOL;?>
LOCATION:<?php echo $address['groundName'].', '.$address['addressLine1'].', '.$address['postCode'].PHP_EOL;?>
BEGIN:VALARM<?php echo PHP_EOL;?>
TRIGGER:-PT15M<?php echo PHP_EOL;?>
ACTION:DISPLAY<?php echo PHP_EOL;?>
DESCRIPTION:Reminder<?php echo PHP_EOL;?>
END:VALARM<?php echo PHP_EOL;?>
END:VEVENT<?php
        echo PHP_EOL;
    }
}
?>
END:VCALENDAR
