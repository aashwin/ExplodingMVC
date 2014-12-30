/**
 * Created by aashwin on 30/12/14.
 */
(function( $ ) {
    $.fn.scroll_paginate = function( options ) {
        var opts = $.extend({
            nextElement: ".next_paginate",
            autoLoadNext: true,
            autoLoadTimes: false,
            loadingText:'Loading More Content...',
            completeText:"That's All! Everything Loaded :)",
            appendURL: ''
        }, options );

        init(this);
        $this=this;
        $(window).bind('scroll.scroll_paginate',function (){
           // console.log($this.data("scroll_waiting") +"+"+ $(window).scrollTop() +"+"+  ($this.outerHeight() -$this.offset().top));
            if ($this.data("scroll_waiting")==0 && $(window).scrollTop() + $(window).height() == $(document).height()) {
                if($(opts.nextElement, $this).length==0){
                    finish();
                    return;
                }
                $(opts.nextElement, $this).hide();
                var nextURL=getNextURL($this);
                if(nextURL=='' || nextURL==undefined){
                    finish();
                    return;
                }
                loadNext($this, nextURL);
            }
        });
        return this;
        function finish(){
            $(window).unbind('scroll.scroll_paginate');
            $(".loadingScroll").remove();
            $('<div class="loadingScroll">'+opts.completeText+'</div>').appendTo($this);
        }
        function init($this){
            if($(opts.nextElement, $this).length==0){
                finish();
                return;
            }
            $(opts.nextElement, $this).hide();
            var nextURL=getNextURL($this);
            if(nextURL=='' || nextURL==undefined){
                finish();
                return;
            }
            $this.data("scroll_waiting", 0);
        }
        function getNextURL($this){
            return $(opts.nextElement, $this).attr("href")+opts.appendURL;
        }
        function loadNext($this, $url){
            $this.data("scroll_waiting", 1);
            console.log("Loading Next URL: "+ $url);
            $(opts.nextElement, $this).remove();
            $loadingElement=$('<div class="loadingScroll">'+opts.loadingText+'</div>').appendTo($this);
                $('<div class="scroll_page"></div>').appendTo($this).load($url, function(response, status, xhr){
                    if ( status == "error" ) {
                        alert(xhr.status + " " + xhr.statusText);
                        finish();
                        return;
                    }
                    $loadingElement.remove();
                    $this.data("scroll_waiting", 0);
                    console.log("Loaded URL: "+ $url);
                });
        }

    };
}( jQuery ));