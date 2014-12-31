<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: view.view.php
 * Date: 31/12/14
 * Time: 16:25
 */
$data=$this->getViewArray('teamsData');
if($this->getViewArray('currentPage')==1) {

?>
<div class="white_gradient maindetails">

    <div>
        <img class="image grid_mob_3 grid_med_2"
             src="<?php echo WWW_TEAM_FLAG; ?>/<?php echo $data['teamFlag']; ?>"/></div>
    <div class="details grid_mob_9 grid_med_10">
        <div class="inner ">
            <h1>Team: <?php echo $data['teamName']; ?></h1>

            <p>Events Involved: <?php echo $this->getViewArray('totalItems'); ?>
                <br/>
                <a href="<?php echo Functions::pageLink('ical','teams', $data['teamId']);?>">Download the Calender (.ics)</a>

            </p>
        </div>
    </div>
    <div class="clear"></div>

</div>
<div align="center">
    <small><span class="notMobile">Click/</span>Tap an event to see more details :)</small>
    <br />
    <h2>Upcoming Fixtures</h2>
</div>

<div id="scroll_pagination">
<?php
}
if($this->getViewArray('eventsData')!==false){
    $teamModel=$this->getViewArray('teamsModel');
    while($r=$this->getViewArray('eventsData')->fetch()){
        $teamOne=$teamModel->getTeam($r['teamOne']);
        $teamTwo=$teamModel->getTeam($r['teamTwo']);
        ?>
        <div class="grid_mob_12 grid_med_6">
            <a href="<?php echo Functions::pageLink('Events', 'view', $r['eventId'],$this->getViewArray('eventsModel')->buildName($r['eventName'], $teamOne['teamName'],$teamTwo['teamName']));?>" class="fixture_box white_gradient">
                <div class="grid_mob_12 ">
                    <div class="title" align="center"><?php echo $this->getViewArray('eventsModel')->buildName($r['eventName'], $teamOne['teamName'],$teamTwo['teamName']);?></div>
                </div>
                <div class="grid_mob_5" align="center">
                    <div class="fixture_img" ><img src="<?php echo WWW_TEAM_FLAG.$teamOne['teamFlag'];?>"></div>
                    <?php echo $teamOne['teamName'];?>
                </div>
                <div class="grid_mob_2 fixture_vs" align="center"><strong>vs</strong></div>
                <div class="grid_mob_5" align="center">
                    <div class="fixture_img" ><img src="<?php echo WWW_TEAM_FLAG.$teamTwo['teamFlag'];?>"></div>
                    <?php echo $teamTwo['teamName'];?>
                </div>
                <div class="countdown_style grid_mob_12">
                    <?php
                    $timeLeft=strtotime($r['startTime'])-time();
                    if($timeLeft>0){
                        ?>
                        <strong>Match Starts in:</strong>
                        <div class="countdown" data-time="<?php echo $timeLeft;?>">
                            <?php
                            echo Functions::timeTill(strtotime($r['startTime']),
                                '<div class="grid_mob_3 countdowneffect"><div class="days">%d</div></div>
                                 <div class="grid_mob_3 countdowneffect"><div class="hours">%h</div></div>
                                 <div class="grid_mob_3 countdowneffect"><div class="minutes">%m</div></div>
                                 <div class="grid_mob_3 countdowneffect"><div class="seconds">%s</div></div>'
                            );?>
                            <div class="clear"></div>
                        </div>
                    <?php }else{?>
                        <strong>Match Was On:</strong>
                        <div class="grid_mob_12 countdowneffect"><div><?php echo date(DISPLAY_DATETIME, strtotime($r['startTime']));?></div></div>
                        <div class="clear"></div>

                    <?php }?>

                </div>
                <div class="clear"></div>

            </a>
        </div>
    <?php
    }

}?><div class="clear"></div>
<?php
$totalPages=ceil($this->getViewArray('totalItems')/$this->getViewArray('perPage'));
if($this->getViewArray('currentPage')<$totalPages){
    echo '<a href="'.Functions::pageLink($this->getController(), $this->getAction(), $data['teamId'], $data['teamName'], $this->getViewArray('currentPage')+1).'" class="next_paginate">Load More Events</a>';
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