<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events_main.view.php
 * Date: 17/11/14
 * Time: 21:20
 */
$tournamentsModel=$this->loadModel('tournaments');
$addressModel=$this->loadModel('address');
if($this->getViewArray('ajax')===false){

    ?>
    <script>var currentPage=<?php echo $this->getViewArray('currentPage');?>;</script>
    <nav>
        <ul>
            <li><a href="<?php echo Functions::pageLink($this->getController(), 'addEvent');?>">Add New Event</a></li>

        </ul>
    </nav>
    <table width="100%" data-sorturl="<?php echo Functions::pageLink($this->getController(),$this->getAction(),$this->getViewArray('currentPage'),$this->getViewArray('perPage'), '-order-','-by-');?>">
    <thead>
    <tr><th width="10%" data-order="eventId">ID</th><th width="20%" data-order="eventName">Name</th><th width="20%" data-order="tournamentId">Tournament</th><th width="15%" data-order="addressId">Address</th><th width="15%" data-order="startTime">Start</th><th width="30%">Action</th></tr>

    </thead>
    <tbody id="pagination-content">
<?php
}
$query=$this->getViewArray('GetEvents');
try{

if($query!==false){
    while($row=$query->fetch()){
        echo '<tr>
                        <td>'.$row['eventId'].'</td>
                        <td>'.$this->getViewArray('EventsModel')->buildName($row['eventName'], $row['teamOne'],$row['teamTwo']).'</td>
                        <td>'.$tournamentsModel->getTournament($row['tournamentId'],'tournamentName').'</td>
                        <td>'.$addressModel->getAddress($row['addressId'],'addressLine1').'</td>
                        <td>'.date(DB_DATETIME_FORMAT, strtotime($row['startTime'])).'</td>
                        <td><a href="'.Functions::pageLink($this->getController(),'editEvent', $row['eventId']).'" class="button">Edit</a>
                        <a class="Delete button" data-name="'.addslashes($row['eventName']).'" data-id="'.($row['eventId']).'" href="'.Functions::pageLink($this->getController(),'DeleteEvent', $row['eventId']).'">Delete</a></td>
                        </tr>';
    }
}

}catch(PDOException $e){
    $e->getTraceAsString();
}
if($this->getViewArray('ajax')===false){

    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="7" id="pagination" data-link="<?php echo Functions::pageLink($this->getController(),$this->getAction(), '{page}', $this->getViewArray('perPage'), $this->getViewArray('order'), $this->getViewArray('by'));?>" data-perpage="<?php echo $this->getViewArray('perPage');?>" data-totalitems="<?php echo $this->getViewArray('EventsModel')->getEvents()->rowCount();?>"></td>
    </tr>
    </tfoot>
    </table>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/events.js"></script>
<?php }?>