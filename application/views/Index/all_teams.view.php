<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: all_teams.view.php
 * Date: 31/12/14
 * Time: 15:15
 */
$data=$this->getViewArray('teamsData');
if($this->getViewArray('currentPage')==1) {
    ?>
    <div class="white_gradient maindetails">

        <div class="details grid_mob_12">
            <div class="inner ">
                <h1>Teams</h1>

            </div>
        </div>
        <div class="clear"></div>

    </div>
    <div align="center">
        <small><span class="notMobile">Click/</span>Tap an team to see more details :)</small>
    </div>
<div id="scroll_pagination">

<?php
}

if($data!==false){

    while($r=$data->fetch()){

        ?>
        <div class="grid_mob_6 grid_med_3">
            <a href="<?php echo Functions::pageLink("Index", "getTeam", $r['teamId'], $r['teamName']);?>" class="series_box">
                <img src="<?php echo WWW_TEAM_FLAG.$r['teamFlag'];?>"  />
                <div class="title"><?php echo $r['teamName'];?></div>
            </a>
        </div>
<?php
    }

}?><div class="clear"></div>

<?php
$totalPages=ceil($this->getViewArray('totalItems')/$this->getViewArray('perPage'));
if($this->getViewArray('currentPage')<$totalPages){
    echo '<a href="'.Functions::pageLink($this->getController(), $this->getAction(), $this->getViewArray('currentPage')+1).'" class="next_paginate">Load More Teams</a>';
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