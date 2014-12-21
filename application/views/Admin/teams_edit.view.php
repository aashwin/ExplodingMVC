<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: teams_edit.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
 $data=$this->getViewArray('TeamData');
echo $this->breadcrumbs();

?>
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['teamId']);?>" method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>Edit Team: <?php echo $data['teamName'];?></legend>
        <label for="teamName">Team Name </label>
        <div class="required">Required*</div>
        <input type="text" name="teamName" id="teamName" required value="<?php echo $data['teamName'];?>" placeholder="Enter a name for the team...">
       <?php if($data['teamFlag']!=''){?>
        <div  class="HalfWidthInput" style="float:left;" >
            <label for="teamFlag">Current Flag </label>
            <input type="hidden" name="flagFile" value="<?php echo $data['teamFlag'];?>">
           <div class="inputfield"align="center"><img src="<?php echo WWW_TEAM_FLAG.$data['teamFlag'];?>" />
            <br />
            <input type="checkbox" value="1" name="removeFlag" id="removeFlag" selected="false"> Remove
           </div>
        </div>
        <div  class="HalfWidthInput" style="float:left;margin-left:1.7%">
            <?php }?>
            <label for="teamFlag">New Team Flag </label>
            <input type="file" id="teamFlag" name="teamFlag" />
        <?php if($data['teamFlag']!=''){ ?></div><?php }?>
        <div class="clear"></div>
            <input type="submit" value="Edit Team">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>
