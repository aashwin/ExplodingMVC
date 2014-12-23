<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: teams_main.view.php
 * Date: 17/11/14
 * Time: 21:20
 */
if($this->getViewArray('ajax')===false){
    echo $this->breadcrumbs();


    ?>
    <script>var currentPage=<?php echo $this->getViewArray('currentPage');?>;</script>
<nav>
    <ul>
        <li><a href="<?php echo Functions::pageLink($this->getController(), 'addTeam');?>">Add New Team</a></li>

    </ul>
</nav>
<table width="100%" data-inlinesave="<?php echo Functions::pageLink($this->getController(),'editTeam', '-id-', '-field-');?>" data-sorturl="<?php echo Functions::pageLink($this->getController(),$this->getAction(),$this->getViewArray('currentPage'),$this->getViewArray('perPage'), '-order-','-by-');?>">
    <thead>
    <tr><th width="10%" data-order="teamId">ID</th><th width="50%" data-order="teamName">Team Name</th><th width="25%">Action</th></tr>
    <tr class="filter" data-filter="<?php echo Functions::pageLink($this->getController(),$this->getAction(), $this->getViewArray('currentPage'), $this->getViewArray('perPage'), $this->getViewArray('order'), $this->getViewArray('by'));?>">
        <td><input type="text" name="teamId" placeholder="Filter ID" /></td>
        <td><input type="text" name="teamName" placeholder="Filter Name" /></td>
        <td></td>
    </tr>
    </thead>
    <tbody id="pagination-content">
<?php
}
        $query=$this->getViewArray('GetTeams');
        if($query!==false){
            while($row=$query->fetch()){
               echo '<tr data-fid="'.$row['teamId'].'">
                        <td>'.$row['teamId'].'</td>
                        <td class="inline_edit" data-field="teamName">'.$row['teamName'].'</td>

                        <td><a href="'.Functions::pageLink($this->getController(),'editTeam', $row['teamId']).'" class="btn_pencil button"><span class="notMobile">Edit</span></a>
                        <a class="Delete btn_delete button" data-name="'.addslashes($row['teamName']).'" data-id="'.($row['teamId']).'" href="'.Functions::pageLink($this->getController(),'DeleteTeam', $row['teamId']).'"><span class="notMobile">Delete</span></a></td>
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