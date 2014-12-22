/**
 * Created by aashwin on 22/12/14.
 */
var placeholderSupport;
$(document).ready(function(){
    placeholderSupport=document.createElement('input');
    placeholderSupport='placeholder' in placeholderSupport;
    if(placeholderSupport){
        $(".field").each(function(){
            var $label= $("label", $(this));
            $label.animate({opacity:0, top:'6px'},500);

            $("input", $(this)).keyup(function(){
                if($(this).val()!=''){
                    $label.animate({opacity:1, top:'-5px'},500);
                }else{
                    $label.animate({opacity:0, top:'6px'},500);

                }
            }).attr("placeholder", $label.html());

        });
    }

});