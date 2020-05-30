(function($){
"use strict";

    // Tab Menu
    function woolentor_admin_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('wlactive').parent().siblings().children('a').removeClass('wlactive');
            $( $tabpane + $target ).addClass('wlactive').siblings().removeClass('wlactive');
        });
    }
    woolentor_admin_tabs( $(".woolentor-admin-tabs"), '.woolentor-admin-tab-pane' );

    var contenttypeval = admin_wllocalize_data.contenttype;
    if( contenttypeval == 'fakes' ){
        $(".notification_fake").show();
        $(".notification_real").hide();
    }else{
        $(".notification_fake").hide();
        $(".notification_real").show();
    }
    // When Change radio button
    $(".notification_content_type .radio").on('change',function(){
        if( $(this).is(":checked") ){
            contenttypeval = $(this).val();
        }
        if( contenttypeval == 'fakes' ){
            $(".notification_fake").show();
            $(".notification_real").hide();
        }else{
            $(".notification_fake").hide();
            $(".notification_real").show();
        }
    });
    
})(jQuery);