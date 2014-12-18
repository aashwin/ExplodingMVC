<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: teams_edit.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
 $data=$this->getViewArray('TeamData');
?>
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['teamId']);?>" method="POST">
    <fieldset>
        <legend>Edit Team: <?php echo $data['teamName'];?></legend>
        <label for="teamName">Team Name </label>
        <div class="required">Required*</div>
        <input type="text" name="teamName" id="teamName" required value="<?php echo $data['teamName'];?>" placeholder="Enter a name for the team...">

            <input type="submit" value="Edit Team">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>
