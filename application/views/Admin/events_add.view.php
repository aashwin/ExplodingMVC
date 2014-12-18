<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events_add.view.php
 * Date: 17/11/14
 * Time: 21:49
 */
?>
<form action="<?php echo Functions::pageLink($this->getController(), $this->getAction());?>" method="POST">
    <fieldset>
        <legend>Add New Event</legend>
        <div class="hint">HINT: use [%team1%] OR [%team2%] variables for event name.</div>

        <div class="HalfWidthInput">
            <label for="eventName">Event Name</label>
            <div class="required">Required*</div>
            <input type="text" name="eventName" id="eventName" required <?php if(!empty($_SESSION['FormData']['eventName'])){echo 'value="'.$_SESSION['FormData']['eventName'].'"';}?> placeholder="Enter a name for the event...">
        </div>
        <div class="HalfWidthInput" style="margin-left:1.7%">
            <label for="tournamentId">Tournament</label>
            <div class="required">Required*</div>
            <select name="tournamentId" required>
                <option value="0">--Select Tournament--</option>
                <?php
                $query=$this->getViewArray('TournamentsModel')->getTournaments();
                if($query!==false){
                    while($row=$query->fetch()){
                        echo '<option value="'.$row['tournamentId'].'" '.((intval($_SESSION['FormData']['tournamentId'])==$row['tournamentId'])?'selected':'').' >'.$row['tournamentName'].'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="HalfWidthInput">
            <label for="eventName">Team #1</label>
            <div class="required">Required*</div>
            <select name="team1" required>
                <option value="0">--Select Team--</option>
                <?php
                $query=$this->getViewArray('TeamsModel')->getTeams();
                if($query!==false){
                    while($row=$query->fetch()){
                        echo '<option value="'.$row['teamId'].'" '.((intval($_SESSION['FormData']['team1'])==$row['teamId'])?'selected':'').' >'.$row['teamName'].'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="HalfWidthInput" style="margin-left:1.7%">
            <label for="tournamentId">Team #2</label>
            <div class="required">Required*</div>
            <select name="team2" required>
                <option value="0">--Select Team--</option>
                <?php
                $query=$this->getViewArray('TeamsModel')->getTeams();
                if($query!==false){
                    while($row=$query->fetch()){
                        echo '<option value="'.$row['teamId'].'" '.((intval($_SESSION['FormData']['team2'])==$row['teamId'])?'selected':'').' >'.$row['teamName'].'</option>';
                    }
                }
                ?>
            </select>

        </div>
        <div id="ExistingAddress">
            <div class="HalfWidthInput">
                <label for="addressId">Ground Address</label>
                <div class="required">Required*</div>
                <select name="addressId" id="addressId">
                    <option value="0">--Pick a existing address--</option>
                    <?php
                    $query=$this->getViewArray('AddressModel')->getAddresses();
                    if($query!==false){
                        while($row=$query->fetch()){
                            $locations[]=$row['addressLine1'].', '.$row['postCode'].', '.$this->getViewArray('CountriesModel')->getCountry($row['countryId'],'countryName');
                            echo '<option value="'.$row['addressId'].'" '.(($data['addressId']==$row['addressId'])?'selected':'').'>'.$locations[count($locations)-1].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="HalfWidthInput" style="margin-left:1.7%">
                <label for="AddNewAddress" class="notMobile">Address</label>
                <input type="button" class="button" id="AddNewAddress" value="New Address">
            </div>
        </div>
        <div id="NewAddressForm" style="display:none">
            <input type="button" class="button" id="ShowExistingAddress" value="Existing Address">
            <div class="HalfWidthInput">
                <label for="addressLine1">Address Line 1</label>
                <div class="required">Required*</div>
                <input type="text" name="addressLine1" id="addressLine1" required <?php if(!empty($_SESSION['FormData']['addressLine1'])){echo 'value="'.$_SESSION['FormData']['addressLine1'].'"';}?> placeholder="">
            </div>
            <div class="HalfWidthInput" style="margin-left:1.7%">
                <label for="addressLine2">Address Line 2</label>
                <input type="text" name="addressLine2" id="addressLine2" <?php if(!empty($_SESSION['FormData']['addressLine2'])){echo 'value="'.$_SESSION['FormData']['addressLine2'].'"';}?> placeholder="">
            </div>
            <div class="HalfWidthInput">
                <label for="postCode">Postcode</label>
                <input type="text" name="postCode" id="postCode"  <?php if(!empty($_SESSION['FormData']['postCode'])){echo 'value="'.$_SESSION['FormData']['postCode'].'"';}?> placeholder="">
            </div>
            <div class="HalfWidthInput" style="margin-left:1.7%">
                <label for="addressLine2">Country</label>
                <select name="countryId">
                    <option value="0">--Pick a Country--</option>
                    <?php
                    $query=$this->getViewArray('CountriesModel')->getCountries();
                    if($query!==false){
                        while($row=$query->fetch()){
                            echo '<option value="'.$row['countryId'].'" '.((intval($_SESSION['FormData']['countryId'])==$row['countryId'])?'selected':'').' >'.$row['countryName'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div id="locationOnMap" class="HalfWidthInput"></div>
        <label for="startTime">Start Date/Time</label>
        <input type="text" name="startTime" id="startTime"  placeholder=""<?php if(!empty($_SESSION['FormData'])){echo 'value="'.$_SESSION['FormData']['startTime'].'"';}?>>

            <input type="submit" value="Add Event">
    </fieldset>
</form>
<?php
    unset($_SESSION['FormData']);
?>
<script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/jquery-ui-timepicker.js"></script>
<script type="text/javascript">
    var jsonLocations=<?php echo json_encode($locations);?>;
</script><script type="text/javascript" src="<?php echo WWW_PUBLIC;?>/js/admin/events_modify.js"></script>