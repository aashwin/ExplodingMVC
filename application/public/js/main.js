/**
 * Created by aashwin on 11/11/14.
 */
var filter_timer;
$(document).ready(function(){
    overlayDiv=buildOverlay();
    $("#showMenu").click(function(){
        $("#overlay").fadeTo(300, 0.8);
        $("#navigation").stop().animate({'left':0,'opacity':1}, 400);

    });
    $("#closeMenu").click(function(){
        $("#overlay").fadeOut(300);
        $("#navigation").stop().animate({'left':-301,'opacity':0}, 400);

    });
    function updateTime(){
        $(".countdown").each(function(){
            //var timer;
            var secondsLeft=parseInt($(this).data("time"));
            secondsLeft--;
            $(this).data("time", secondsLeft);
            var days = Math.floor(secondsLeft / 86400);
            secondsLeft %= 86400;

            var hours = Math.floor(secondsLeft / 3600);
            secondsLeft %= 3600;

            var minutes = Math.floor(secondsLeft / 60);
            secondsLeft %= 60;
            var $this=$(this);
            if(minutes!=parseInt($(".minutes", $this).html())){
                $(".minutes", this).animate({'opacity':0}, 200,
                    function(){
                        $(".minutes", $this).html(minutes).animate({'opacity':1}, 200);
                    });
            }
            if(hours!=parseInt($(".hours", $this).html())){
                $(".hours", this).animate({'opacity':0}, 200,
                    function(){
                        $(".hours", $this).html(hours).animate({'opacity':1}, 200);
                    });
            }
            if(days!=parseInt($(".days", $this).html())){
                $(".days", this).animate({'opacity':0}, 200,
                    function(){
                        $(".days", $this).html(days).animate({'opacity':1}, 200);
                    });
            }
            if(secondsLeft!=parseInt($(".seconds", $this).html())){
                $(".seconds", this).animate({'opacity':0}, 200,
                    function(){
                    $(".seconds", $this).html(secondsLeft).animate({'opacity':1}, 200);
                    });
            }

        });
        setTimeout(updateTime, 1000);
    }
    updateTime();

    $("#tournamentField").change(function(){
        var val=$(this).val();
        if(val==0)
            return;
        showLoading();
        $.get(WWW_ROOT+'/Index/getTournaments/'+val+'/1/ajax')
            .done(function(d) {
                $("#content").html(d);
                history.pushState("contentLoaded", document.title, WWW_ROOT+'/Index/getTournaments/'+val);

            })
            .fail(function(e) {
                console.log(e);
                alert("Failed to load content..." );
            })
            .always(function() {
                hideLoading();
            });

    });

});
function showLoading(){
    $("#LoadingBox").fadeIn(100);

}
function hideLoading(){
    $("#LoadingBox").fadeOut(300);

}
function buildOverlay(){

    if(!$('#ModularOverlay').length){
        $("body").prepend('<div id="ModularOverlay" class="overlay">')
            .prepend('<div id="LoadingBox"><img src="'+WWW_PUBLIC+'/images/admin/ajax.gif" /> </div>');
    }
    return $("#ModularOverlay");
}
