<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: teams_add.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
echo $this->breadcrumbs();

?>
 <form enctype="multipart/form-data" action="<?php echo Functions::pageLink($this->getController(), $this->getAction());?>" method="POST">
    <fieldset>
        <legend>Add New Team</legend>
       <div class="field grid_mob_12 grid_med_12">
           <label for="teamName">Team Name </label>
            <div class="required">Required*</div>
             <input type="text" name="teamName" id="teamName" required <?php if(!empty($_SESSION['FormData']['teamName'])){echo 'value="'.$_SESSION['FormData']['teamName'].'"';}?> placeholder="Enter a name for the team...">
        </div>
        <div class="field grid_mob_12 grid_med_12">

            <label for="teamFlag">Team Flag </label>
            <input type="file" id="teamFlag" name="teamFlag" />
        </div>
        <input type="submit" value="Add Team">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>