<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: Index.view.php
 * Date: 29/10/14
 * Time: 23:05
 */
$this->loadView('Index','search_form', false);
?>
<?php
if($this->getViewArray('likedData')!==false){
    ?>
    <div style="padding:5px;">
        <h1>Events You Like...</h1></div>

<?php
    $teamModel=$this->loadModel('teams');
    while($r=$this->getViewArray('likedData')->fetch()){
        $this->addViewArray('rData', $r);
        $this->addViewArray('teamOneData', $teamModel->getTeam($r['teamOne']));
        $this->addViewArray('teamTwoData', $teamModel->getTeam($r['teamTwo']));
        $this->loadView('Events', 'event_box',false);
    }

}
?>
<div class="clear"></div>
<div style="padding:5px;">
<h1>Upcoming Matches</h1></div>
<?php
if($this->getViewArray('upcomingData')!==false){
    $teamModel=$this->loadModel('teams');
    while($r=$this->getViewArray('upcomingData')->fetch()){
        $this->addViewArray('rData', $r);
        $this->addViewArray('teamOneData', $teamModel->getTeam($r['teamOne']));
        $this->addViewArray('teamTwoData', $teamModel->getTeam($r['teamTwo']));
        $this->loadView('Events', 'event_box',false);
    }
}else{
    echo 'No Events Coming Up';
}
?>
<div class="clear"></div>