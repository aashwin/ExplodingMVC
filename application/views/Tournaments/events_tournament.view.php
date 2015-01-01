<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events_tournament.view.php
 * Date: 27/12/14
 * Time: 23:05
 */
$data=$this->getViewArray('tournamentData');
if($this->getViewArray('currentPage')==1) {
    ?>
    <div class="white_gradient maindetails">
        <div>
            <img class="image grid_mob_3 grid_med_2"
                 src="<?php echo WWW_TOURNAMENT_IMG;?>/<?php echo $data['image'];?>"/></div>
        <div class="details grid_mob_9 grid_med_10">
            <div class="inner ">
                <h1><?php echo $data['tournamentName'];?></h1>

                <p>
                    <small>Total Events: <?php echo $this->getViewArray('totalItems');?></small> <br />
                    <strong>Start on </strong> <?php echo date(DISPLAY_DATE, strtotime($data['tournamentStart']));?> <br />

                    <strong>Ends on</strong> <?php echo date(DISPLAY_DATE, strtotime($data['tournamentEnd']));?> <br />
                    <a href="<?php echo Functions::pageLink('ical','tournaments',$data['tournamentId']);?>"class="logout_btn" style="width:210px;float:right">Download Calender (.ics)</a>
                    <div class="clear"></div>
                </p>
            </div>
        </div>
        <div class="clear"></div>

    </div>
    <div align="center">
        <small><span class="notMobile">Click/</span>Tap an event to see more details :)</small>
    </div>
    <div align="center" class="sort_container">
        Sort: <a href="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['tournamentId'], $this->getViewArray('currentPage'), 'startTime', $this->getViewArray('orderBy'));?>" class="white_gradient sort_btn <?php echo ($this->getViewArray('order')=='startTime'?'sort_selected':'');?>">Start Date</a>
        <a href="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['tournamentId'],  $this->getViewArray('currentPage'), 'eventName', $this->getViewArray('orderBy'));?>" class="white_gradient sort_btn <?php echo ($this->getViewArray('order')=='eventName'?'sort_selected':'');?>">Event Name</a>
        By: <a href="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['tournamentId'],  $this->getViewArray('currentPage'), $this->getViewArray('order'), 'asc');?>" class="white_gradient sort_btn <?php echo ($this->getViewArray('orderBy')=='asc'?'sort_selected':'');?>">Asc<span class="notMedium">ending</span></a>
        <a href="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['tournamentId'],  $this->getViewArray('currentPage'), $this->getViewArray('order'), 'desc');?>" class="white_gradient sort_btn <?php echo ($this->getViewArray('orderBy')=='desc'?'sort_selected':'');?>">Desc<span class="notMedium">ending</span></a></div>
    <div id="scroll_pagination">
<?php
}
if($this->getViewArray('eventsData')!==false){
    $teamModel=$this->loadModel('teams');
    while($r=$this->getViewArray('eventsData')->fetch()){
        $this->addViewArray('rData', $r);
        $this->addViewArray('teamOneData', $teamModel->getTeam($r['teamOne']));
        $this->addViewArray('teamTwoData', $teamModel->getTeam($r['teamTwo']));
        $this->loadView('Events', 'event_box',false);
    }

}?><div class="clear"></div>
    <?php
$totalPages=ceil($this->getViewArray('totalItems')/$this->getViewArray('perPage'));
if($this->getViewArray('currentPage')<$totalPages){
    echo '<a href="'.Functions::pageLink($this->getController(), $this->getAction(), $data['tournamentId'], $this->getViewArray('currentPage')+1,$this->getViewArray('order'),$this->getViewArray('orderBy')).'" class="next_paginate">Load More Events</a>';
}
?>
</div>

<div class="clear"></div>

<?php
if($this->getViewArray('currentPage')==1) {
?>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/scroll_paginate.jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#scroll_pagination").scroll_paginate({
            appendURL:'/ajax',
            loadingText:'<img src="<?php echo WWW_PUBLIC;?>/images/ajax.gif" />'
        });
    });
</script>
<?php }
?>