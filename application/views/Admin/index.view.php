<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: index.view.php
 * Date: 17/11/14
 * Time: 20:08
 */
echo $this->breadcrumbs();

?>
<h1>Quick Menu</h1>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'addTeam');?>" class="button quickmenu">Add Team</a>
</div>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'addTournament');?>" class="button quickmenu">Add Tournament</a>
</div>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'addEvent');?>" class="button quickmenu">Add Event</a>
</div>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'addUser');?>" class="button quickmenu">Add User</a>
</div>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'teams');?>" class="button quickmenu">List Teams</a>
</div>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'tournaments');?>" class="button quickmenu">List Tournaments</a>
</div>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'events');?>" class="button quickmenu">Lists Event</a>
</div>
<div class="grid_mob_12 grid_med_3">
    <a href="<?php echo Functions::pageLink($this->getController(), 'users');?>" class="button quickmenu">List Users</a>
</div>
<div class="clear"></div>