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
$rating=$this->getViewArray('eventRating');
$ratedAlready=$this->getViewArray('eventRatingsModel')->alreadyRated($this->userModel->loggedInUserData('userId'), $data['eventId']);

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
<?php

?>
<div class="rating_bar" <?php echo ($ratedAlready!=1?'':'style="width:100px"');?>>
    <?php

    if($ratedAlready!=1) {
        ?><a href="#" class="like_btn"><img src="<?php echo WWW_PUBLIC;?>/images/like.png" width="100" height="30"/></a>
    <?php
    }
    ?>
    <div class="rate_sec <?php echo ($ratedAlready!=1?'':'addRadius');?>"><span class="rate_text"><?php echo round($rating,2);?>%</span><div class="likes" style="width:50%"></div></div>
    <?php
    if($ratedAlready!=1) {
    ?>
         <a href="#" class="dislike_btn"><img src="<?php echo WWW_PUBLIC;?>/images/dislike.png" width="100" height="30" /></a>
    <?php
    }
    ?>
</div>
<?php
$this->addViewArray('rData', $data);
$this->addViewArray('teamOneData', $teamOne);
$this->addViewArray('teamTwoData', $teamTwo);
$this->loadView('Events', 'event_box',false);
?>
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
        $this->addViewArray('rData', $r);
        $this->addViewArray('teamOneData', $teamModel->getTeam($r['teamOne']));
        $this->addViewArray('teamTwoData', $teamModel->getTeam($r['teamTwo']));
        $this->loadView('Events', 'event_box',false);
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
    $(".like_btn").click(function(e){
        e.preventDefault();
        rateEvent(<?php echo $data['eventId'];?>,1);
        return false;
    });
    $(".dislike_btn").click(function(e){
        e.preventDefault();

        rateEvent(<?php echo $data['eventId'];?>,0);
        return false;
    });
    function rateEvent(id, rate){
        $.post('<?php echo Functions::pageLink('Events', 'Rate');?>', { 'id': id, 'rate':rate  }, function( data ) {
                if(data.error==true){
                    if(data.type=='login'){
                        $(".rate_text").html("Login to vote");
                    }else{
                        $(".rate_text").html("Error");
                    }
                }else{
                    $(".rate_sec").addClass("addRadius");
                    $(".like_btn, .dislike_btn").fadeOut(300, function(){
                        $(".rating_bar").css({width:100});
                        $(".rate_text").html(data.rating+"%");
                        $(".likes").animate({width:data.rating}, 300);
                    });


                }
        }, "json");
    }
</script>
<div class="clear"></div>
