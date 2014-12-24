<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments_edit.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
$data=$this->getViewArray('TournamentData');
echo $this->breadcrumbs();

?>
<form enctype="multipart/form-data" action="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['tournamentId']);?>" method="POST">
    <fieldset>
        <legend>Edit Tournament: <?php echo $data['tournamentName'];?></legend>
        <div class="field grid_mob_12 grid_med_12">
            <label for="tournamentName">Tournament Name </label>
            <div class="required">Required*</div>
            <input type="text" name="tournamentName" id="tournamentName" required value="<?php echo $data['tournamentName'];?>" placeholder="Enter a name for the tournament...">
        </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="tournamentStart">Start Date</label>
            <input type="text" name="tournamentStart" id="tournamentStart"  placeholder="" value="<?php echo $data['tournamentStart'];?>">
        </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="tournamentEnd">End Date</label>
            <input type="text" name="tournamentEnd" id="tournamentEnd"  placeholder="" value="<?php echo $data['tournamentEnd'];?>">
        </div>
        <?php if($data['image']!=''){?>
        <div  class="field grid_mob_12 grid_med_6" >
            <label for="imageFile">Current Image </label>
            <input type="hidden" name="imageFile" value="<?php echo $data['image'];?>">
            <div class="inputfield" align="center"><img src="<?php echo WWW_TOURNAMENT_IMG.$data['image'];?>" />
                <br />
                <input type="checkbox" value="1" name="removeImage" id="removeImage" selected="false"> Remove
            </div>
        </div>
        <div  class="field grid_mob_12 grid_med_6">
            <?php }else{
            ?>
            <div class="field grid_mob_12 grid_med_12">
                <?php }?>
                <label for="teamFlag">New Image </label>
                <input type="file" id="image" name="image" />
            </div>
            <div class="clear"></div>
        <input type="submit" value="Edit Tournament">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>
<script>
    $("#tournamentStart").datepicker({
    } );
    $("#tournamentEnd").datepicker({
    } );
</script>