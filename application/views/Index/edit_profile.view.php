<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: edit_profile.view.php
 * Date: 02/1/15
 * Time: 17:19
 */
?>
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction());?>" method="POST" enctype="multipart/form-data">
   <h1>Edit Profile</h1>
    <?php echo $this->getViewArray('Success'); ?>
    <div class="searchfield">
        <label for="userPassword" style="width:200px;display: inline-block;">New Password (leave as blank)</label>
        <input type="password" name="userPassword" value="" placeholder="Remain Unchanged">
    </div>
    <div class="searchfield">
        <label for="profileImage"style="width:200px;display: inline-block;">New Profile Pic</label>
        <input type="file" name="profileImage" id="profileImage">
</div>
    <div class="clear"></div>
            <input type="submit" name="updateUser" class="searchbutton" value="Edit Profile">
    <div class="clear"></div>

</form>