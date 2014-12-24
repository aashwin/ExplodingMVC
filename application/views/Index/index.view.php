<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: Index.view.php
 * Date: 29/10/14
 * Time: 23:05
 */

?>
<h1>Tournaments</h1>
<?php
    $getTournaments=$this->getViewArray('TournamentsModel')->getHomeList();
    if($getTournaments!==false){
        while($row=$getTournaments->fetch()){
            echo '<div class="grid_mob_6 grid_med_4 grid_lrg_3"><div class="box"><img src="'.WWW_TOURNAMENT_IMG.$row['image'].'"></div></div>';
        }
    }

?>
