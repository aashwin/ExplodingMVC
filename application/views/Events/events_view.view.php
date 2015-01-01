<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: events_view.view.php
 * Date: 30/12/14
 * Time: 17:05
 */
$data=$this->getViewArray('eventData');
$teamOne=$this->getViewArray('TeamOne');
$teamTwo=$this->getViewArray('TeamTwo');
$address=$this->getViewArray('GroundAddress');
    ?>
    <div class="white_gradient maindetails">

        <div class="details grid_mob_12">
            <div class="inner ">
                <h1><?php echo $this->getViewArray('eventsModel')->buildName($data['eventName'], $teamOne['teamName'], $teamTwo['teamName']);?></h1>

                <p>
                    <small>Teams Involved: <?php echo $teamOne['teamName'];?> and <?php echo $teamTwo['teamName'];?></small> <br />
                    <strong>Start on </strong> <?php echo date(DISPLAY_DATETIME, strtotime($data['startTime']));?> <br />
                    <strong>Ground Location: </strong> <span id="groundLocation"><?php echo $address['groundName']
                                                        .($address['addressLine1']!=''?', '.$address['addressLine1']:'')
                                                        .($address['addressLine2']!=''?', '.$address['addressLine2']:'')
                                                        .($address['postCode']!=''?', '.$address['postCode']:'')
                                                        .($address['countryId']!=0?', '.$this->loadModel('countries')->getCountry($address['countryId'],'countryName'):'');?></span>

                </p>
            </div>
        </div>
        <div class="clear"></div>

    </div>
<div class="grid_mob_12 grid_med_6">
    <div class="fixture_box white_gradient">
        <div class="grid_mob_12 ">
            <div class="title" align="center"><?php echo $this->getViewArray('eventsModel')->buildName($data['eventName'], $teamOne['teamName'],$teamTwo['teamName']);?></div>
        </div>
        <div class="grid_mob_5" align="center">
            <div class="fixture_img" ><img src="<?php echo WWW_TEAM_FLAG.$teamOne['teamFlag'];?>"></div>
            <?php echo $teamOne['teamName'];?>
        </div>
        <div class="grid_mob_2 fixture_vs" align="center"><strong>vs</strong></div>
        <div class="grid_mob_5" align="center">
            <div class="fixture_img" ><img src="<?php echo WWW_TEAM_FLAG.$teamTwo['teamFlag'];?>"></div>
            <?php echo $teamTwo['teamName'];?>
        </div>
        <div class="countdown_style grid_mob_12">
            <?php
            $timeLeft=strtotime($data['startTime'])-time();
            if($timeLeft>0){
                ?>
                <strong>Match Starts in:</strong>
                <div class="countdown" data-time="<?php echo $timeLeft;?>">
                    <?php
                    echo Functions::timeTill(strtotime($data['startTime']),
                        '<div class="grid_mob_3 countdowneffect"><div class="days">%d</div></div>
                                 <div class="grid_mob_3 countdowneffect"><div class="hours">%h</div></div>
                                 <div class="grid_mob_3 countdowneffect"><div class="minutes">%m</div></div>
                                 <div class="grid_mob_3 countdowneffect"><div class="seconds">%s</div></div>'
                    );?>
                    <div class="clear"></div>
                </div>
            <?php }else{?>
                <strong>Match Was On:</strong>
                <div class="grid_mob_12 countdowneffect"><div><?php echo date(DISPLAY_DATETIME, strtotime($data['startTime']));?></div></div>
                <div class="clear"></div>

            <?php }?>

        </div>
        <div class="clear"></div>

    </div>
</div>
<div class="grid_mob_12 grid_med_6">
    <div id="locationOnMap" >

    </div>
</div>

<div class="clear"></div>
<?php
$similarQ=$this->getViewArray('eventsModel')->similarEvents($data['eventId'], $data['tournamentId'],4);
if($similarQ!==false) {
    ?>
    <div align="center"><h1>Similar Matches</h1></div>

<?php
    $teamModel=$this->loadModel('teams');
    while($r=$similarQ->fetch()){
        $teamOne=$teamModel->getTeam($r['teamOne']);
        $teamTwo=$teamModel->getTeam($r['teamTwo']);
        ?>
        <div class="grid_mob_12 grid_med_6">
            <a href="<?php echo Functions::pageLink('Events', 'view', $r['eventId'], $this->getViewArray('eventsModel')->buildName($r['eventName'], $teamOne['teamName'],$teamTwo['teamName']));?>" class="fixture_box white_gradient">
                <div class="grid_mob_12 ">
                    <div class="title" align="center"><?php echo $this->getViewArray('eventsModel')->buildName($r['eventName'], $teamOne['teamName'],$teamTwo['teamName']);?></div>
                </div>
                <div class="grid_mob_5" align="center">
                    <div class="fixture_img" ><img src="<?php echo WWW_TEAM_FLAG.$teamOne['teamFlag'];?>"></div>
                    <?php echo $teamOne['teamName'];?>
                </div>
                <div class="grid_mob_2 fixture_vs" align="center"><strong>vs</strong></div>
                <div class="grid_mob_5" align="center">
                    <div class="fixture_img" ><img src="<?php echo WWW_TEAM_FLAG.$teamTwo['teamFlag'];?>"></div>
                    <?php echo $teamTwo['teamName'];?>
                </div>
                <div class="countdown_style grid_mob_12">
                    <?php
                    $timeLeft=strtotime($r['startTime'])-time();
                    if($timeLeft>0){
                        ?>
                        <strong>Match Starts in:</strong>
                        <div class="countdown" data-time="<?php echo $timeLeft;?>">
                            <?php
                            echo Functions::timeTill(strtotime($r['startTime']),
                                '<div class="grid_mob_3 countdowneffect"><div class="days">%d</div></div>
                                         <div class="grid_mob_3 countdowneffect"><div class="hours">%h</div></div>
                                         <div class="grid_mob_3 countdowneffect"><div class="minutes">%m</div></div>
                                         <div class="grid_mob_3 countdowneffect"><div class="seconds">%s</div></div>'
                            );?>
                            <div class="clear"></div>
                        </div>
                    <?php }else{?>
                        <strong>Match Was On:</strong>
                        <div class="grid_mob_12 countdowneffect"><div><?php echo date(DISPLAY_DATETIME, strtotime($r['startTime']));?></div></div>
                        <div class="clear"></div>

                    <?php }?>

                </div>
                <div class="clear"></div>

            </a>
        </div>
    <?php
    }
}
?>
<script type="text/javascript">
    $(document).ready(function(){

        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
        'callback=mapInit&key=<?php echo GOOGLE_MAPS_API_KEY;?>';
        document.body.appendChild(script);
    });
    function mapInit() {
        var mapOptions = {
            zoom: 1,
            center: new google.maps.LatLng(0, 0)
        };

        map = new google.maps.Map(document.getElementById('locationOnMap'),
            mapOptions);
        infowindow = new google.maps.InfoWindow();
            var geocoder = new google.maps.Geocoder();
            var address=document.getElementById('groundLocation').innerHTML;
            geocoder.geocode({'address': address}, function (results, status) {

                if (status == google.maps.GeocoderStatus.OK) {
                    $("#locationOnMap").slideDown(600);
                    var bounds = new google.maps.LatLngBounds();

                   var marker;
                    marker=new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location,
                            title: document.getElementById('groundLocation').innerHTML
                        });
                    marker.setMap(map);

                        bounds.extend( marker.getPosition());


                    map.fitBounds(bounds);
                    map.setZoom(map.getZoom()-1);
                    if(map.getZoom()>12){
                        map.setZoom(12);
                    }



                }

            });




    }
</script>
<div class="clear"></div>
