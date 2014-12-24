/**
 * Created by aashwin on 11/11/14.
 */
$(document).ready(function(){

    $("#showMenu").click(function(){
        $("#overlay").fadeTo(300, 0.8);
        $("#navigation").stop().animate({'left':0,'opacity':1}, 400);

    });
    $("#closeMenu").click(function(){
        $("#overlay").fadeOut(300);
        $("#navigation").stop().animate({'left':-301,'opacity':0}, 400);

    });
});
