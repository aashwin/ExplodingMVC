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
    <table width="100%" data-sorturl="<?php echo Functions::pageLink($this->getController(),$this->getAction(),$this->getViewArray('currentPage'),$this->getViewArray('perPage'), '-order-','-by-');?>">
    <thead>
    <tr><th width="10%" data-order="tournamentId">ID</th><th width="20%" data-order="tournamentName">Name</th><th width="15%" data-order="tournamentStart">Start</th><th width="15%" data-order="tournamentEnd">End</th><th width="30%">Action</th></tr>

    </thead>
    <tbody id="pagination-content">
<?php
}
$query=$this->getViewArray('GetTournaments');

if($query!==false){
    while($row=$query->fetch()){
        echo '<tr>
                        <td>'.$row['tournamentId'].'</td>
                        <td>'.$row['tournamentName'].'</td>
                        <td>'.date('jS M y', strtotime($row['tournamentStart'])).'</td>
                        <td>'.date('jS M y', strtotime($row['tournamentEnd'])).'</td>
                        <td><a href="'.Functions::pageLink($this->getController(),'editTournament', $row['tournamentId']).'" class="button">Edit</a>
                        <a class="Delete button" data-name="'.addslashes($row['tournamentName']).'" data-id="'.($row['tournamentId']).'" href="'.Functions::pageLink($this->getController(),'DeleteTournament', $row['tournamentId']).'">Delete</a></td>
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