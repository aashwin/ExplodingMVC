<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: sports_edit.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
$getSport=$this->getViewArray('SportData');
?>
    <form action="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $getSport['sportId']);?>" method="POST">
        <fieldset>
            <legend>Edit  Sport: <?php echo $getSport['sportName'];?></legend>
            <label for="sportName">Sport Name </label>
            <div class="required">Required*</div>
            <input type="text" name="sportName" id="sportName" required value="<?php echo $getSport['sportName'];?>" placeholder="Enter a name for the sport...">
            <label for="navOrder">Navigation Order </label>
            <input type="number" name="navOrder" id="navOrder" value="<?php echo $getSport['navOrder'];?>" placeholder="Enter navigation order...">
            <input type="submit" value="Update Sport">
        </fieldset>
    </form>
<?php
unset($_SESSION['FormData']);
?>