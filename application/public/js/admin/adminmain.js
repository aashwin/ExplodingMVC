/**
 * Created by aashwin on 11/11/14.
 */
/*CONFIG VARIABLES*/

/*Other*/
var overlayDiv;
var msgCounter=0;
var admin_settings={
    deleteMessage:'Are you sure you want to delete this?'
}
$(document).ready(function(){
    overlayDiv=buildOverlay();
    $(".success, .error").each(function(){
        $(this).attr("id", 'cx'+msgCounter);
        var lastCounter=msgCounter;

        setTimeout(function(){
            $("#cx"+lastCounter).slideUp(600);
            $("#cx"+lastCounter).remove();
        },5000);
        ++msgCounter;
    });
    $(".showMenu").click(function(){
        if( $("#navigation").offset().left>=0) {
            $("#navigation").stop().animate({'left':-$("#navigation").outerWidth(true),'opacity':0}, 1000);
        }else{
            $("#navigation").css("left",-$("#navigation").outerWidth(true));
            $("#navigation").stop().animate({'left':0,'opacity':1}, 1000);
        }
    });
    $(document).on("click",".Delete",function(e){
        var id=$(this).data("id");
        var name=$(this).data("name");
        var deleteMessage=admin_settings.deleteMessage+'<br /> <strong>Name: </strong> '+name+' - <strong>ID: </strong> '+id;
        return showConfirmation(deleteMessage, ajax_Delete, id);
    });
});
function buildOverlay(){

    if(!$('#ModularOverlay').length){
        $("body").prepend('<div id="ModularOverlay" class="overlay">').prepend(
        '   <div id="BoxConfirm" class="ConfirmBox">' +
        '    <div class="button title" id="ConfirmTitle">Are You Sure?</div>' +
            '<div class="message" id="ConfirmMessage"></div>'+
            '<a id="ConfirmCancel" class="button">Cancel</a><a class="button"id="ConfirmOK">Confirm</a>'+
        '</div>' +
        '</div>').prepend('<div id="LoadingBox"><img src="'+WWW_PUBLIC+'/images/admin/ajax.gif" /> </div>');
    }
    return $("#ModularOverlay");
}
function showLoading(){
    $("#LoadingBox").fadeIn(100);

}
function hideLoading(){
    $("#LoadingBox").fadeOut(300);


}
function updateLoading(value, max){
}
function showConfirmation(msg, callback, id){
    id = (typeof id === "undefined") ? 0 : id;
    overlayDiv.fadeTo(1000, 0.8);
    $("#BoxConfirm").delay(800).fadeIn(1000);
    $("#ConfirmMessage").html(msg);
    $("#ConfirmOK").off('click').click(function(){

        if(id==0)
            callback();
        else
            callback(id);
        hideConfirmation();
    });
    $("#ConfirmCancel").off('click').click(function(){
       hideConfirmation();
    });
    return false;

}
function hideConfirmation(){
    $("#BoxConfirm").fadeOut(300);
    overlayDiv.delay(100).fadeOut(300);

}
function addMessage(error, msg){
    $('<div></div>', {
        'style':'display:none',
        'class':error,
        'id':'cx'+msgCounter
    }).prependTo('#container').html('<span>'+msg+'</span>').slideDown(600, function(){$('span',this).fadeIn(500);});
    var lastCounter=msgCounter;
    setTimeout(function(){
        $("#cx"+lastCounter).slideUp(600);
        $("#cx"+lastCounter).remove();
    },5000);
    ++msgCounter;
}
function pagination(id, contentId, itemsPerPage, totalItems, linkSample){

    var totalPages=Math.ceil(parseInt(totalItems)/parseInt(itemsPerPage));

    $id=$(id);
    $id.html("");
    var link='';
    if(currentPage>1){
        link=(linkSample.replace('-page-', currentPage-1));
        $id.append(' <a href="'+link+'" data-page="'+(currentPage-1)+'" class="paginator_page button">&lt;&lt; Previous</a> ');
    }
    for(var i=1;i<=totalPages;++i){
        $id.append(' <a href="'+(linkSample.replace('-page-', i))+'" data-page="'+i+'" class="notMobile paginator_page button">'+i+'</a> ');
    }
    if(currentPage<totalPages){
        $id.append(' <a href="'+(linkSample.replace('-page-', currentPage+1))+'" data-page="'+(currentPage+1)+'" class="paginator_page button">Next &gt;&gt;</a> ');
    }
    $(".paginator_page").click(function(event){
        event.preventDefault();
        var pageToGo=$(this).data("page");
        if(pageToGo==currentPage){return false;}
        
        $this=$(this);
        showLoading();
        $.ajax({
            type: 'GET',
            url: $this.attr("href")+'/ajax',
            data: {},
            success: function(data){
                $(contentId).html(data);
                history.pushState("contentLoaded", document.title, $this.attr("href"));
                $("html, body").animate({ scrollTop: "0" },600);
				currentPage=pageToGo;
                hideLoading();
            }
        });

       pagination(id,contentId, itemsPerPage, totalItems, linkSample);
        return false;
    });
}
function pageSort(s){
    $(s).each(function(){
        var th=$(this);
        if(th.data("order")==undefined){
            return;
        }
        var linkSample=$(this).parent().parent().parent().data("sorturl");
        $("<a></a>",{
            'class':'sorticons asc'
        })
            .html('<img src="'+WWW_PUBLIC+'/images/admin/view_sort_ascending.png" width="24" height="24" />')
            .prependTo(th)
            .off('click')
            .on('click', function(e){
                sortAjax(linkSample,'#pagination-content',th.data("order"), 'ASC');
            });

        $("<a></a>",{
            'class':'sorticons desc'
        })
            .html('<img src="'+WWW_PUBLIC+'/images/admin/view_sort_descending.png" width="24" height="24" />')
            .prependTo(th)
            .off('click')
            .on('click', function(e){
                sortAjax(linkSample,'#pagination-content',th.data("order"), 'DESC');
            });

    });
}
function sortAjax(linkSample,contentId, order, by){
    $this=$(this);
    var url=linkSample.replace("-order-", order).replace("-by-", by);
    console.log(url);

    showLoading();
    $.ajax({
        type: 'GET',
        url: url+'/ajax',
        data: {},
        success: function(data){
            $(contentId).html(data);
            history.pushState("contentLoaded", document.title, url);
            $("html, body").animate({ scrollTop: "0" },300);
            hideLoading();
        }
    });
}
function ajax_Delete(id){
    var url=$('.Delete[data-id="'+id+'"]').attr("href")+'/ajax/';
    $.get(url, function(data){
        if(data.return=='success'){
            $('.Delete[data-id="'+id+'"]').parent().parent().slideUp(1000);
        }
        addMessage((data.return), data.msg);

    }, 'json');
}