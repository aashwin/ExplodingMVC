<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events_edit.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
 $data=$this->getViewArray('EventData');
echo $this->breadcrumbs();
?>
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction(), $data['eventId']);?>" method="POST">
    <fieldset>
        <legend>Edit Event<span class="notMobile">: <?php echo $this->getViewArray('EventsModel')->buildName($data['eventName'], $data['teamOne'], $data['teamTwo']);?></span></legend>
        <div class="hint">HINT: use [%team1%] OR [%team2%] variables for event name.</div>

        <div class="field grid_mob_12 grid_med_6">
            <label for="eventName">Event Name </label>
            <div class="required">Required*</div>
             <input type="text" name="eventName" id="eventName" required value="<?php echo $data['eventName'];?>" placeholder="Enter a name for the event...">
       </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="tournamentId">Tournament</label>
            <div class="required">Required*</div>
            <select name="tournamentId">
                <option value="0">--Select Tournament--</option>
                <?php
                $query=$this->getViewArray('TournamentsModel')->getTournaments();
                if($query!==false){
                    while($row=$query->fetch()){
                        echo '<option value="'.$row['tournamentId'].'" '.(($data['tournamentId']==$row['tournamentId'])?'selected':'').' required>'.$row['tournamentName'].'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="eventName">Team #1</label>
            <div class="required">Required*</div>
            <select name="team1" required>
                <option value="0">--Select Team--</option>
                <?php
                $query=$this->getViewArray('TeamsModel')->getTeams();
                if($query!==false){
                    while($row=$query->fetch()){
                        echo '<option value="'.$row['teamId'].'" '.(($data['teamOne']==$row['teamId'])?'selected':'').' >'.$row['teamName'].'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="field grid_mob_12 grid_med_6">
            <label for="tournamentId">Team #2</label>
            <div class="required">Required*</div>
            <select name="team2" required>
                <option value="0">--Select Team--</option>
                <?php
                $query=$this->getViewArray('TeamsModel')->getTeams();
                if($query!==false){
                    while($row=$query->fetch()){
                        echo '<option value="'.$row['teamId'].'" '.(($data['teamTwo']==$row['teamId'])?'selected':'').' >'.$row['teamName'].'</option>';
                    }
                }
                ?>
            </select>

        </div>
        <div id="ExistingAddress">
            <div class="field grid_mob_12 grid_med_6">
                <label for="addressId">Address</label>
                <div class="required">Required*</div>
                <select name="addressId" id="addressId">
                    <option value="0">--Pick a existing address--</option>
                    <?php
                    $query=$this->getViewArray('AddressModel')->getAddresses();
                    $locations=array();
                    if($query!==false){
                        while($row=$query->fetch()){
                            $locations[]=$row['groundName'].','.$row['addressLine1'].', '.$row['postCode'].', '.$this->getViewArray('CountriesModel')->getCountry($row['countryId'],'countryName');
                            echo '<option value="'.$row['addressId'].'" '.(($data['addressId']==$row['addressId'])?'selected':'').'>'.$locations[count($locations)-1].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="field grid_mob_12 grid_med_6">
                <label for="AddNewAddress" class="notMobile">Address</label>
                <input type="button" class="button" id="AddNewAddress" value="New Address">
            </div>
        </div>
        <div id="NewAddressForm" style="display:none">
            <input type="button" class="button" id="ShowExistingAddress" value="Existing Address">
            <div class="field grid_mob_12 grid_med_12">
                <label for="addressLine1">Ground Name</label>
                <div class="required">Required*</div>
                <input type="text" name="groundName" id="groundName"   placeholder="">
            </div>
            <div class="field grid_mob_12 grid_med_6">
                <label for="addressLine1">Address Line 1</label>
                <div class="required">Required*</div>
                <input type="text" name="addressLine1" id="addressLine1"   placeholder="">
            </div>
            <div class="field grid_mob_12 grid_med_6">
                <label for="addressLine2">Address Line 2</label>
                <input type="text" name="addressLine2" id="addressLine2"  placeholder="">
            </div>
            <div class="field grid_mob_12 grid_med_6">
                <label for="postCode">Postcode</label>
                <input type="text" name="postCode" id="postCode"  placeholder="">
            </div>
            <div class="field grid_mob_12 grid_med_6">
                <label for="addressLine2">Country</label>
                <select name="countryId">
                    <option value="0">--Pick a Country--</option>
                    <?php
                    $query=$this->getViewArray('CountriesModel')->getCountries();
                    if($query!==false){
                        while($row=$query->fetch()){
                            echo '<option value="'.$row['countryId'].'" >'.$row['countryName'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div id="locationOnMap"></div>
        <div class="field grid_mob_12 grid_med_12">
            <label for="startTime">Start Date/Time</label>
            <input type="text" name="startTime" id="startTime"  placeholder="" value="<?php echo date(DISPLAY_DATETIME,strtotime($data['startTime']));?>">

        </div>
            <input type="submit" value="Edit Event">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-ui-timepicker.js"></script>
<script type="text/javascript">
    var jsonLocations=<?php echo json_encode($locations);?>;
</script>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/events_modify.js"></script>