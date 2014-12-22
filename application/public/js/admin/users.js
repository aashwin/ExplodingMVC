/**
 * Created by aashwin on 18/11/14.
 */

$(document).ready(function(){
    pageSort('thead th');
    pagination('#pagination','#pagination-content', $("#pagination").data("perpage"), $("#pagination").data("totalitems"), $("#pagination").data("link"));
});


