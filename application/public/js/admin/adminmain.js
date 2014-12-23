/**
 * Created by aashwin on 11/11/14.
 */
/*CONFIG VARIABLES*/

/*Other*/
var overlayDiv;
var msgCounter=0;
var inline_editing=false;
var inline_save_url;
var filter_timer;
var pagination_settings={
    id:'',
    contentId:'',
    itemsPerPage:0,
    totalItems:0,
    unfilteredTotalItems:0,
    linkSample:''
};
var admin_settings={
    deleteMessage:'Are you sure you want to delete this?'
}
$(document).ready(function(){
    overlayDiv=buildOverlay();
    inline_save_url=$("table").first().data("inlinesave");
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
    if(inline_save_url!=undefined){
        $(document).on("click",".Delete",function(e){
            var id=$(this).data("id");
            var name=$(this).data("name");
            var deleteMessage=admin_settings.deleteMessage+'<br /> <strong>Name: </strong> '+name+' - <strong>ID: </strong> '+id;
            return showConfirmation(deleteMessage, ajax_Delete, id);
        });
        $(document).on("click",".inline_edit",function(e){
            if($(this).hasClass('inline_editing')){return false;}
                var currentVal = $(this).html();
            $(this).data("defaultVal",currentVal).html("").addClass("inline_editing");
                $("<input />").appendTo($(this))
                    .attr(
                    {
                        type: 'text',
                        name: 'inline_' + $(this).data('field'),
                        id: 'inline_editor'

                    }).val(currentVal).focus().addClass("inline_editor");
                var editor=$('input',this);
                $('<a id="inline_cancel" class="save_btn red_btn" />')
                    .html("Cancel")
                    .appendTo("body")
                    .css({
                        top: editor.offset().top,
                        left: editor.offset().left
                    }).animate({left:editor.offset().left+editor.width()-5}, 1000);

        }).on("blur", '.inline_editor', function(e){
            editor = $(this);
            if (editor.parent().data("defaultVal") != editor.val()){
                $("#inline_cancel").html("Saving").addClass("yellow_btn").removeClass("red_btn");
                $.post(
                    inline_save_url
                        .replace('-id-', parseInt(editor.closest('tr').data('fid')))
                        .replace('-field-', editor.parent().data("field")),
                    {'updateValue' : editor.val()},
                    function(data){
                        if(data.return=='success') {
                            var cancel_button= $("#inline_cancel");
                            cancel_button.html("Saved").addClass("green_btn").removeClass("yellow_btn");
                            editor.parent().removeClass("inline_editing");
                            editor.closest('td').addClass('success');
                            cancel_button.animate(
                                {left:editor.parent().offset().left, opacity:0}, 1000,
                                function(){
                                    cancel_button.remove();
                                });
                            editor.parent().html(editor.val());

                        }else{
                            editor.closest('td').addClass('error');
                            $("#inline_cancel").html("Failed").addClass("red_btn").removeClass("yellow_btn");
                        }
                    },
                    "JSON"
                );
            }else{
                editor.parent().removeClass("inline_editing");
                editor.parent().html(editor.val());
                $("#inline_cancel").fadeOut(500,function(){$(this).remove();});

            }
        }).on("click", '#inline_cancel',function(){

            var editor = $("#inline_editor");
            editor.parent().removeClass("inline_editing");
            editor.parent().html(editor.parent().data("defaultVal"));
            $("#inline_cancel").fadeOut(500,function(){$(this).remove();});
        });

    }
    $(".filter").on('change', 'select', function(){
        doAjaxFilter($(this));
    }).on('keyup', 'input', function(){
        doAjaxFilter($(this));
    });
});
function doAjaxFilter($this){
    clearTimeout(filter_timer);
    filter_timer=setTimeout(function(){

            showLoading();
        var name=$this.attr("name");

        if($this.val()=='')
            name = '';
            $.post(
                $(".filter").data("filter")+'/ajax/',
                {'field':name, 'value':$this.val()},
                function(data){
                    $("#pagination-content").html(data);
                    if(name!=''){
                        pagination_settings.totalItems=1;

                        buildPagination(name, $this.val());
                    }else{
                        pagination_settings.totalItems=pagination_settings.unfilteredTotalItems;

                        buildPagination();
                    }
                    hideLoading();
                },"html"
            );

    },900);
}
function occurrences(string, subString, allowOverlapping){

    string+=""; subString+="";
    if(subString.length<=0) return string.length+1;

    var n=0, pos=0;
    var step=(allowOverlapping)?(1):(subString.length);

    while(true){
        pos=string.indexOf(subString,pos);
        if(pos>=0){ n++; pos+=step; } else break;
    }
    return(n);
}
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
function buildPagination(field, value){
    $id=$(pagination_settings.id);
    $id.html("");
    var totalPages=Math.ceil(parseInt(pagination_settings.totalItems)/parseInt(pagination_settings.itemsPerPage));
    if(currentPage>1){
        $id.append(' <a href="'+pagination_settings.linkSample.replace('-page-', currentPage-1)+'" data-page="'+(currentPage-1)+'" class="paginator_page button">&lt;&lt; Previous</a> ');
    }
    for(var i=1;i<=totalPages;++i){
        $id.append(' <a href="'+(pagination_settings.linkSample.replace('-page-', i))+'" data-page="'+i+'" class="notMobile paginator_page button">'+i+'</a> ');
    }
    if(currentPage<totalPages){
        $id.append(' <a href="'+(pagination_settings.linkSample.replace('-page-', currentPage+1))+'" data-page="'+(currentPage+1)+'" class="paginator_page button">Next &gt;&gt;</a> ');
    }
    $(".paginator_page").click(function(event){
        event.preventDefault();
        var pageToGo=$(this).data("page");
        if(pageToGo==currentPage){return false;}

        $this=$(this);
        showLoading();
        var $data={'field':'', 'value':''};

        if(field!=undefined && value!=undefined && field!='undefined' && value!='undefined'){
            $data.field=field;
            $data.value=value;
            if($data.value=='')
                $data.field='';
        }
        $.ajax({
            type: 'POST',
            url: $this.attr("href")+'/ajax',
            data: $data,
            success: function(data){
                $("html, body").animate({ scrollTop: "0px" },600);
                history.pushState("contentLoaded", document.title, $this.attr("href"));

                $("#table-div_"+pagination_settings.contentId.replace("#",'').replace('.','')).slideUp(600, function(){
                    $(pagination_settings.contentId).html(data);
                    $("#table-div_"+pagination_settings.contentId.replace("#",'').replace('.','')).slideDown(400);
                });
                currentPage=pageToGo;
                hideLoading();
               //
                if(field!=undefined && value!=undefined && field!='undefined' && value!='undefined') {
                    buildPagination(field, value);
                }else{
                    buildPagination();
                }
            }
        });
        return false;
    });
}
function pagination(id, contentId, itemsPerPage, totalItems, linkSample){
    pagination_settings.id=id;
    pagination_settings.contentId=contentId;
    pagination_settings.itemsPerPage=itemsPerPage;
    pagination_settings.totalItems=totalItems;
    pagination_settings.unfilteredTotalItems=totalItems;
    pagination_settings.linkSample=linkSample;
    $(contentId).parent().wrap('<div id="table-div_'+contentId.replace("#",'').replace('.','')+'" />');
    buildPagination();
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
            $('.Delete[data-id="'+id+'"]').parent().parent().addClass('error').find('td')

                .wrapInner('<div style="display: block;" />')
                .parent()
                .find('td > div')

                .slideUp(1000,function(){
                    $('.Delete[data-id="'+id+'"]').parent().parent().parent().remove();
                });
        }
        addMessage((data.return), data.msg);

    }, 'json');
}
