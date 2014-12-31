/**
 * Created by aashwin on 22/12/14.
 */
var placeholderSupport;
$(document).ready(function(){
    placeholderSupport=document.createElement('input');
    placeholderSupport='placeholder' in placeholderSupport;
    if(placeholderSupport){
        var acceptInputs='input[type=text],input[type=password],input[type=date],input[type=email]';
        $(".field").each(function(){
            var $label= $("label", $(this));

            $(this).on('change keyup', 'input', function(){doLabelAnimate($(this),$label);});
            if($(acceptInputs,this).attr("placeholder", $label.html()).val()!=''){
                doLabelAnimate($(acceptInputs,this),$label);
            }else{
                $label.animate({opacity:0, top:'6px'},500);
            }

        });
    }

});
function doLabelAnimate($this,$label){
    if($this.val()!=''){
        $label.animate({opacity:1, top:'-9px'},500);
    }else{
        $label.animate({opacity:0, top:'6px'},500);

    }
}