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
    <div id="locationOnMap">

    </div>
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