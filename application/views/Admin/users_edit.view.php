<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events_edit.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
 $data=$this->getViewArray('UserData');
echo $this->breadcrumbs();
?>
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['userId']);?>" method="POST">
    <fieldset>
        <legend>Edit User</legend>

        <div class="field grid_mob_12 grid_med_6">
            <label for="username">Username </label>
            <div class="required">Required*</div>
             <input type="text" name="username" id="username" required value="<?php echo $data['username'];?>" placeholder="Username...">
       </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="userLevel">User Level</label>
            <div class="required">Required*</div>
            <select name="userLevel">
                <option value="0" <?php echo (($data['userLevel']==0)?'selected':'');?>>Normal User</option>
                <option value="1" <?php echo (($data['userLevel']==1)?'selected':'');?>>Admin</option>

            </select>
        </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="eventName">New Password</label>
            <div class="required">Leave as blank (unchanged)</div>
            <input type="password" name="userPassword" value="" placeholder="Remain Unchanged">
        </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="tournamentId">Email Address:</label>
            <div class="required">Required*</div>
            <input type="text" name="userEmail" id="userEmail" required value="<?php echo $data['userEmail'];?>" placeholder="Email...">


        </div>

            <input type="submit" value="Edit User">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>