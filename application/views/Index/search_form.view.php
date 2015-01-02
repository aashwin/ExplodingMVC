<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2015
 * File: search_form.view.php
 * Date: 02/01/15
 * Time: 15:16
 */
?>
<h1>Search Events</h1>
<form name="Search" action="<?php echo Functions::pageLink('Index','Search');?>" method="POST">

    <div class=" grid_mob_6 grid_med_3">
        <div class="field searchfield">

            <label for="q">Events/Teams</label>
            <input type="text" name="q" id="q" value="<?php echo htmlentities(strip_tags($_POST['q']));?>">
        </div>
    </div>
    <div class=" grid_mob_6 grid_med_3">
        <div class="field searchfield">

            <label for="q">Ground/Address</label>
            <input type="text" name="where" id="where" value="<?php echo htmlentities(strip_tags($_POST['where']));?>">
        </div>
    </div>
    <div class=" grid_mob_6 grid_med_3">
        <div class="field searchfield">

            <label for="q">From (Date)</label>
            <input type="text" name="fromDate" id="fromDate" value="<?php echo htmlentities(strip_tags($_POST['fromDate']));?>">
        </div>
    </div>
    <div class=" grid_mob_6 grid_med_3">
        <div class="field searchfield">

            <label for="q">To (Date)</label>
            <input type="text" name="toDate" id="toDate" value="<?php echo htmlentities(strip_tags($_POST['toDate']));?>">
        </div>
    </div>
    <div class=" grid_mob_12 grid_med_12">
        <div class="field searchfield">
            <input type="submit" class="searchbutton" value="Search Events...">
        </div>
    </div>
    <div class="clear"></div>
</form>
<script type="text/javascript">$("#fromDate, #toDate").datepicker({});</script>