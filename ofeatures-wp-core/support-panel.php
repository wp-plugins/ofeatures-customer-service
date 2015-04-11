<?php

if (!function_exists('support_panel_ofeatures_html_page')){
    
    function support_panel_ofeatures_html_page() {
        $clientId = get_option('ofeatures_clientid');
        global $plugin_name;
        global $settings_label;

        if (get_option('ofeatures_clientid')){ ?>
            <h4 class="support-panl-preloader">
                <?php _e("Loading Support Panel...")?><br/>
                <img  alt="" src="<?php echo plugins_url() . "/".$plugin_name;?>/ofeatures-wp-core/img/preloader.gif" />
            </h4>

            <iframe  id="ofeatures-frame"></iframe>

            <script>
                var clientBasePanelUrl = "https://<?php echo $clientId ?>.panel.ofeatures.com";
                jQuery('#ofeatures-frame').attr('src', clientBasePanelUrl + "/skyblow.clientbackend/customerservice/alert")
                jQuery('#ofeatures-frame').on('load', function(){
                    jQuery(".support-panl-preloader").hide()
                })
                    
                //Backward Wordpress compatibility
                function updateSupportPanelArea(){
                    //size
                    jQuery('#ofeatures-frame').height(jQuery(window).height() - jQuery('#ofeatures-frame').offset().top)
                    
                    if (jQuery(window).width() != jQuery(document).width() || jQuery('.wp-responsive-open').length > 0){
                        jQuery('#ofeatures-frame').width(jQuery(window).width() - jQuery('#ofeatures-frame').offset().left) 
                    }else{
                        jQuery('#ofeatures-frame').width('')
                    }
                    
                    //position
                    if (jQuery('#wp-admin-bar-menu-toggle').length == 0){
                        var leftMenuWidth = jQuery('#adminmenuwrap').width()
                        jQuery('#wpcontent').css('margin-left',leftMenuWidth)
                    }

                    if (jQuery('#wpadminbar').css('position') == "absolute"){
                        jQuery('#ofeatures-frame').css('top',jQuery('#wpadminbar').height())
                    }else{
                        jQuery('#ofeatures-frame').css('top', 0)
                    }
                }
               
                setInterval(function(){
                    updateSupportPanelArea()
                }, 1000)               
               
                jQuery('#collapse-menu, #wp-admin-bar-menu-toggle').click(function(){
                    setTimeout(updateSupportPanelArea, 50)
                })
                
                updateSupportPanelArea()
                
                jQuery(window).resize(function(){
                    updateSupportPanelArea()
                })
            </script>
            
            <style>
                #wpcontent{
                   padding-left:0px;
                }

                .auto-fold #wpcontent {
                   padding-left:0px;
                }
            </style>
            
<?php    } else { ?>
            <h4 class="support-panl-preloader">
                <?php echo (_e("Please provide the correct plugin access data in: Menu > ") . _e("Settings") . " > $settings_label"); ?><br/>
            </h4>

<?php   }
    }
}
?>