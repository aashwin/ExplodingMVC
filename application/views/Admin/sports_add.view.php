<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: sports_add.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
?>
 <form action="<?php echo Functions::pageLink($this->getController(), $this->getAction());?>" method="POST">
    <fieldset>
        <legend>Add New Sport</legend>
        <label for="sportName">Sport Name </label>
        <div class="required">Required*</div>
        <input type="text" name="sportName" id="sportName" required <?php if(!empty($_SESSION['FormData']['sportName'])){echo 'value="'.$_SESSION['FormData']['sportName'].'"';}?> placeholder="Enter a name for the sport...">
        <label for="navOrder">Navigation Order </label>
        <input type="number" name="navOrder" id="navOrder" value="<?php echo $this->getViewArray('NextNavOrder');?>" placeholder="Enter navigation order...">
        <input type="submit" value="Add Sport">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>