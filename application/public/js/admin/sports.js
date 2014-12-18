/**
 * Created by aashwin on 18/11/14.
 */
function ajax_DeleteSport(id){
    var url=$('.DeleteSport[data-id="'+id+'"]').attr("href")+'/ajax/';
    $.get(url, function(data){
        if(data.return=='success'){
            $('.DeleteSport[data-id="'+id+'"]').parent().parent().slideUp(1000);
        }
        addMessage((data.return), data.msg);

    }, 'json');
}
$(document).on("click",".DeleteSport",function(e){
    var id=$(this).data("id");
    var name=$(this).data("name");
    return showConfirmation('Do you want to delete the sport: '+name, ajax_DeleteSport, id);
});

$(document).ready(function(){
    pageSort('thead th');
    pagination('#pagination','#pagination-content', $("#pagination").data("perpage"), $("#pagination").data("totalitems"), $("#pagination").data("link"));
});
