<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: all_tournaments.view.php
 * Date: 30/12/14
 * Time: 16:05
 */
$data=$this->getViewArray('tournamentsData');
    ?>
    <div class="white_gradient maindetails">

        <div class="details grid_mob_12">
            <div class="inner ">
                <h1>Current/Future Series</h1>

            </div>
        </div>
        <div class="clear"></div>

    </div>
<div align="center">
    <small><span class="notMobile">Click/</span>Tap an tournament to see more details :)</small>
</div>
<?php

if($data!==false){

    while($r=$data->fetch()){

        ?>
        <div class="grid_mob_6 grid_med_3">
            <a href="<?php echo Functions::pageLink("Tournaments", "view", $r['tournamentId']);?>" class="series_box">
                <img src="<?php echo WWW_TOURNAMENT_IMG.$r['image'];?>"  />
                <div class="title"><?php echo $r['tournamentName'];?></div>
            </a>
        </div>
<?php
    }

}?><div class="clear"></div>

