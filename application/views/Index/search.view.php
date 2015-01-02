<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: search.view.php
 * Date: 2/1/15
 * Time: 15:05
 */
$this->loadView('Index','search_form', false);
$q=htmlentities(strip_tags($_POST['q']));
$where=htmlentities(strip_tags($_POST['where']));
$fromDate=htmlentities(strip_tags($_POST['fromDate']));
$toDate=htmlentities(strip_tags($_POST['toDate']));
?>
<h1>Search Results</h1>
You searched for <?php echo ($q!=''?'events that contain <strong>'.$q.'</strong>':'any event');?> <?php echo ($where!=''?' in <strong>'.$where.'</strong>':' in any location');?>
<?php echo ($fromDate!=''?' that starts after <strong>'.$fromDate.'</strong>':' that starts at any time');?> <?php echo ($toDate!=''?' that ends before <strong>'.$toDate.'</strong>':' that ends at any time');?>
<br /><br />
<?php
    if($this->getViewArray('searchData')!==false){
        $teamModel=$this->loadModel('teams');
        while($r=$this->getViewArray('searchData')->fetch()){
            $this->addViewArray('rData', $r);
            $this->addViewArray('teamOneData', $teamModel->getTeam($r['teamOne']));
            $this->addViewArray('teamTwoData', $teamModel->getTeam($r['teamTwo']));
            $this->loadView('Events', 'event_box',false);
        }
    }else{
        echo 'No Results Found';
    }
?>
<div class="clear"></div>