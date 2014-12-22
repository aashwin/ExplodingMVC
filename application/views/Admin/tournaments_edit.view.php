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
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['tournamentId']);?>" method="POST">
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