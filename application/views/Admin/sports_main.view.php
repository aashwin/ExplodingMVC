<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: sports_main.view.php
 * Date: 17/11/14
 * Time: 21:20
 */
if($this->getViewArray('ajax')===false){

?>
    <script>var currentPage=<?php echo $this->getViewArray('currentPage');?>;</script>
<nav>
    <ul>
        <li><a href="<?php echo Functions::pageLink($this->getController(), 'addSport');?>">Add New Sport</a></li>

    </ul>
</nav>
<table width="100%" data-sorturl="<?php echo Functions::pageLink($this->getController(),$this->getAction(),$this->getViewArray('currentPage'),$this->getViewArray('perPage'), '-order-','-by-');?>">
    <thead>
    <tr><th width="10%" data-order="sportId">ID</th><th width="50%" data-order="sportName">Sport Name</th><th width="15%" data-order="navOrder">Order</th><th width="25%">Action</th></tr>

    </thead>
    <tbody id="pagination-content">
<?php
}
        $query=$this->getViewArray('GetSports');
        if($query!==false){
            while($row=$query->fetch()){
               echo '<tr>
                        <td>'.$row['sportId'].'</td>
                        <td>'.$row['sportName'].'</td>
                        <td>'.$row['navOrder'].'</td>
                        <td><a href="'.Functions::pageLink($this->getController(),'editSport', $row['sportId']).'" class="button">Edit</a>
                        <a class="DeleteSport button" data-name="'.addslashes($row['sportName']).'" data-id="'.($row['sportId']).'" href="'.Functions::pageLink($this->getController(),'DeleteSport', $row['sportId']).'">Delete</a></td>
                        </tr>';
            }
        }
if($this->getViewArray('ajax')===false){

    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" id="pagination" data-link="<?php echo Functions::pageLink($this->getController(),$this->getAction(), '{page}', $this->getViewArray('perPage'), $this->getViewArray('order'), $this->getViewArray('by'));?>" data-perpage="<?php echo $this->getViewArray('perPage');?>" data-totalitems="<?php echo $this->getViewArray('SportsModel')->getSports()->rowCount();?>"></td>
        </tr>
    </tfoot>
</table>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/sports.js"></script>
<?php }?>