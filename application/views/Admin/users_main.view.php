<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: users_main.view.php
 * Date: 17/11/14
 * Time: 21:20
 */
if($this->getViewArray('ajax')===false){
    echo $this->breadcrumbs();

    ?>
    <script>var currentPage=<?php echo $this->getViewArray('currentPage');?>;</script>
    <nav>
        <ul>
            <li><a href="<?php echo Functions::pageLink($this->getController(), 'addUser');?>">Add New User</a></li>

        </ul>
    </nav>
    <table width="100%" data-inlinesave="<?php echo Functions::pageLink($this->getController(),'editUser', '-id-', '-field-');?>" data-sorturl="<?php echo Functions::pageLink($this->getController(),$this->getAction(),$this->getViewArray('currentPage'),$this->getViewArray('perPage'), '-order-','-by-');?>">
    <thead>
    <tr><th width="8%" data-order="userId">ID</th><th width="18%" data-order="username">Name</th><th width="9%" data-order="userLevel">is Admin</th><th width="35%" data-order="userEmail">Email</th><th width="30%">Action</th></tr>

    </thead>
    <tbody id="pagination-content">
<?php
}
$query=$this->getViewArray('GetUsers');

if($query!==false){
    while($row=$query->fetch()){
        echo '<tr data-fid="'.$row['userId'].'">
                        <td>'.$row['userId'].'</td>
                        <td>'.$row['username'].'</td>
                        <td>'.($row['userLevel']==1?'Yes':'No').'</td>
                        <td>'.$row['userEmail'].'</td>
                        <td><a href="'.Functions::pageLink($this->getController(),'editUser', $row['userId']).'" class="btn_pencil button"><span class="notMobile">Edit</span></a>
                        <a class="Delete btn_delete button" data-name="'.addslashes($row['username']).'" data-id="'.($row['userId']).'" href="'.Functions::pageLink($this->getController(),'DeleteUser', $row['userId']).'"><span class="notMobile">Delete</span></a></td>
                        </tr>';
    }
}

if($this->getViewArray('ajax')===false){

    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="7" id="pagination" data-link="<?php echo Functions::pageLink($this->getController(),$this->getAction(), '{page}', $this->getViewArray('perPage'), $this->getViewArray('order'), $this->getViewArray('by'));?>" data-perpage="<?php echo $this->getViewArray('perPage');?>" data-totalitems="<?php echo $this->getViewArray('UsersModel')->getUsers()->rowCount();?>"></td>
    </tr>
    </tfoot>
    </table>
    <script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/users.js"></script>
<?php }?>