<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: tournaments_main.view.php
 * Date: 17/11/14
 * Time: 21:20
 */
if($this->getViewArray('ajax')===false){
    echo $this->breadcrumbs();

    ?>
    <script>var currentPage=<?php echo $this->getViewArray('currentPage');?>;</script>
    <nav>
        <ul>
            <li><a href="<?php echo Functions::pageLink($this->getController(), 'addTournament');?>">Add New Tournament</a></li>

        </ul>
    </nav>
    <table width="100%" data-inlinesave="<?php echo Functions::pageLink($this->getController(),'editTournament', '-id-', '-field-');?>" data-sorturl="<?php echo Functions::pageLink($this->getController(),$this->getAction(),$this->getViewArray('currentPage'),$this->getViewArray('perPage'), '-order-','-by-');?>">
    <thead>
    <tr><th width="10%" data-order="tournamentId">ID</th><th width="20%" data-order="tournamentName">Name</th><th width="10%" data-order="homepagePriority">Priority</th><th width="15%" data-order="tournamentStart">Start</th><th width="15%" data-order="tournamentEnd">End</th><th width="20%">Action</th></tr>
    <tr class="filter" data-filter="<?php echo Functions::pageLink($this->getController(),$this->getAction(), $this->getViewArray('currentPage'), $this->getViewArray('perPage'), $this->getViewArray('order'), $this->getViewArray('by'));?>">
        <td><input type="text" name="tournamentId" placeholder="Filter ID" /></td>
        <td><input type="text" name="tournamentName" placeholder="Filter Name" /></td>
        <td><input type="text" name="homepagePriority" placeholder="Filter Priority" /></td>
        <td colspan="3"></td>
    </tr>
    </thead>
    <tbody id="pagination-content">
<?php
}
$query=$this->getViewArray('GetTournaments');

if($query!==false){
    while($row=$query->fetch()){
        echo '<tr data-fid="'.$row['tournamentId'].'">
                        <td>'.$row['tournamentId'].'</td>
                        <td class="inline_edit" data-field="tournamentName">'.$row['tournamentName'].'</td>
                        <td class="inline_edit" data-field="homepagePriority">'.$row['homepagePriority'].'</td>
                        <td>'.date('jS M y', strtotime($row['tournamentStart'])).'</td>
                        <td>'.date('jS M y', strtotime($row['tournamentEnd'])).'</td>
                        <td><a href="'.Functions::pageLink($this->getController(),'editTournament', $row['tournamentId']).'" class="btn_pencil button"><span class="notMobile">Edit</span></a>
                        <a class="Delete btn_delete button" data-name="'.addslashes($row['tournamentName']).'" data-id="'.($row['tournamentId']).'" href="'.Functions::pageLink($this->getController(),'DeleteTournament', $row['tournamentId']).'"><span class="notMobile">Delete</span></a></td>
                        </tr>';
    }
}
if($this->getViewArray('ajax')===false){

    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="7" id="pagination" data-link="<?php echo Functions::pageLink($this->getController(),$this->getAction(), '{page}', $this->getViewArray('perPage'), $this->getViewArray('order'), $this->getViewArray('by'));?>" data-perpage="<?php echo $this->getViewArray('perPage');?>" data-totalitems="<?php echo $this->getViewArray('TournamentsModel')->getTournaments()->rowCount();?>"></td>
    </tr>
    </tfoot>
    </table>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/tournaments.js"></script>
<?php }?>