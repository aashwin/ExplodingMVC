/**
 * Created by aashwin on 11/11/14.
 */
$(document).ready(function(){

});
$(".showMenu").click(function(){
    if( $("#navigation").offset().left>=0) {
        $("#navigation").stop().animate({'left':-$("#navigation").outerWidth(true),'opacity':0}, 1000);
    }else{
        $("#navigation").css("left",-$("#navigation").outerWidth(true));
        $("#navigation").stop().animate({'left':0,'opacity':1}, 1000);
    }
});