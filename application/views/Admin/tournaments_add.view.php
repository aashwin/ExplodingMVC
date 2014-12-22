<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments_add.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
echo $this->breadcrumbs();

?>
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction());?>" method="POST">
    <fieldset>
        <legend>Add New Tournament</legend>
        <div class="field grid_mob_12 grid_med_12">
            <label for="tournamentName">Tournament Name </label>
            <div class="required">Required*</div>
            <input type="text" name="tournamentName" id="tournamentName" required <?php if(!empty($_SESSION['FormData']['tournamentName'])){echo 'value="'.$_SESSION['FormData']['tournamentName'].'"';}?> placeholder="Enter a name for the tournament...">
        </div>
        <div class=" field grid_mob_12 grid_med_6">
            <label for="tournamentStart">Start Date</label>
            <input type="text" name="tournamentStart" id="tournamentStart"  placeholder=""<?php if(!empty($_SESSION['FormData'])){echo 'value="'.$_SESSION['FormData']['tournamentStart'].'"';}?>>
        </div>
        <div  class="field  grid_mob_12 grid_med_6">
            <label for="tournamentEnd">End Date</label>
            <input type="text" name="tournamentEnd" id="tournamentEnd"  placeholder=""<?php if(!empty($_SESSION['FormData'])){echo 'value="'.$_SESSION['FormData']['tournamentEnd'].'"';}?>>
        </div>
        <input type="submit" value="Add Tournament">
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