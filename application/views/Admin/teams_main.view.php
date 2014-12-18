<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: teams_main.view.php
 * Date: 17/11/14
 * Time: 21:20
 */
if($this->getViewArray('ajax')===false){

?>
    <script>var currentPage=<?php echo $this->getViewArray('currentPage');?>;</script>
<nav>
    <ul>
        <li><a href="<?php echo Functions::pageLink($this->getController(), 'addTeam');?>">Add New Team</a></li>

    </ul>
</nav>
<table width="100%" data-sorturl="<?php echo Functions::pageLink($this->getController(),$this->getAction(),$this->getViewArray('currentPage'),$this->getViewArray('perPage'), '-order-','-by-');?>">
    <thead>
    <tr><th width="10%" data-order="teamId">ID</th><th width="50%" data-order="teamName">Team Name</th><th width="25%">Action</th></tr>

    </thead>
    <tbody id="pagination-content">
<?php
}
        $query=$this->getViewArray('GetTeams');
        if($query!==false){
            while($row=$query->fetch()){
               echo '<tr>
                        <td>'.$row['teamId'].'</td>
                        <td>'.$row['teamName'].'</td>

                        <td><a href="'.Functions::pageLink($this->getController(),'editTeam', $row['teamId']).'" class="button">Edit</a>
                        <a class="Delete button" data-name="'.addslashes($row['teamName']).'" data-id="'.($row['teamId']).'" href="'.Functions::pageLink($this->getController(),'DeleteTeam', $row['teamId']).'">Delete</a></td>
                        </tr>';
            }
        }
if($this->getViewArray('ajax')===false){

    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" id="pagination" data-link="<?php echo Functions::pageLink($this->getController(),$this->getAction(), '{page}', $this->getViewArray('perPage'), $this->getViewArray('order'), $this->getViewArray('by'));?>" data-perpage="<?php echo $this->getViewArray('perPage');?>" data-totalitems="<?php echo $this->getViewArray('TeamsModel')->getTeams()->rowCount();?>"></td>
        </tr>
    </tfoot>
</table>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/teams.js"></script>
<?php }?>