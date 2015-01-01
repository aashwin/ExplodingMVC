<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: event_box.view.php
 * Date: 1/1/15
 * Time: 17:55
 */
$r=$this->getViewArray('rData');
$teamOne=$this->getViewArray('teamOneData');
$teamTwo=$this->getViewArray('teamTwoData');
?>
        <div class="grid_mob_12 grid_med_6">
            <a href="<?php echo Functions::pageLink('Events', 'view', $r['eventId'], $this->getViewArray('eventsModel')->buildName($r['eventName'], $teamOne['teamName'],$teamTwo['teamName']));?>" class="fixture_box white_gradient">
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