var map;
var infowindow;

function mapInit() {
    var mapOptions = {
        zoom: 1,
        center: new google.maps.LatLng(0, 0)
    };

    map = new google.maps.Map(document.getElementById('locationOnMap'),
        mapOptions);
    infowindow = new google.maps.InfoWindow();
    loadGeoData();


}
$("#addressId").change(function(){
  //loadMapData();
});
var markers=[];
var locationsOnMap=[];
function loadGeoData() {
    for (var xa = 0; xa < jsonLocations.length; ++xa) {
        var geocoder = new google.maps.Geocoder();
        var address=jsonLocations[xa];
        var totalNumToGeoCode=jsonLocations.length;
        geocoder.geocode({'address': address}, function (results, status) {
            totalNumToGeoCode--;
            if (status == google.maps.GeocoderStatus.OK) {

                locationsOnMap.push([results[0].geometry.location]);

            }else{
                locationsOnMap.push(false);
            }

            if(totalNumToGeoCode==0){

                loadMapData();
            }
        });

    }

}
function loadMapData(){
    $("#locationOnMap").slideDown(600);
    var bounds = new google.maps.LatLngBounds();

    markers=[];
    for(var i=0;i<locationsOnMap.length;i++){
        markers.push(new google.maps.Marker({
            map: map,
            position: locationsOnMap[i][0],
            title: jsonLocations[i]
        }));
        markers[i].setMap(map);
        var markerNow=markers[i];
        google.maps.event.addListener(markerNow, 'click', function(me){
            onMarkerClick(me, this);
        });
        bounds.extend( markerNow.getPosition());
    }

    map.fitBounds(bounds);
    map.setZoom(map.getZoom()-1);
    if(map.getZoom()>12){
        map.setZoom(12);
    }
}
$("#addressId").change(function(){
    if($(this).val()==0){
        for(var xas=0;xas<markers.length;xas++){
            markers[xas].setAnimation(null);
        }
        infowindow.close();
        return;
    }
    var markerid=0;
    for(var xas=0;xas<jsonLocations.length;xas++){
        if(jsonLocations[xas]==$("option:selected", this).text())
        {
            markerid=xas;
        }
        markers[xas].setAnimation(null);

    }
    map.panTo(markers[markerid].getPosition());
    markers[markerid].setAnimation(google.maps.Animation.BOUNCE);
    infowindow.setContent(markers[markerid].title);
    infowindow.setPosition(new google.maps.LatLng(markers[markerid].position.lat()+10, markers[markerid].position.lng()));
    infowindow.open(map)
});
function onMarkerClick(me, marker){
    $("#addressId option").each(function() {
        if($(this).text() == marker.title) {

            $(this).prop('selected', true);
        }else{
            $(this).prop('selected', false);
        }
    });
    for(var xas=0;xas<markers.length;xas++){
        markers[xas].setAnimation(null);
    }
    map.panTo(marker.getPosition());
    marker.setAnimation(google.maps.Animation.BOUNCE);
    infowindow.setContent(marker.title);
    infowindow.setPosition(new google.maps.LatLng(marker.position.lat()+10, marker.position.lng()));
    infowindow.open(map)
}


$(document).ready(function(){

    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
    'callback=mapInit&key=AIzaSyCAGSNhf6PGc5_NrmwGOyYirJeh71l8-0k';
    document.body.appendChild(script);
});
$("#startTime").datetimepicker({});
$("#AddNewAddress").click(function(){
    $("#ExistingAddress").slideUp(500,function(){
        $("#NewAddressForm").slideDown(500);
        $("#addressId").val(0);
    });
});
$("#ShowExistingAddress").click(function(){
    $("#NewAddressForm").slideUp(500,function(){
        $("#ExistingAddress").slideDown(500);
    });
});