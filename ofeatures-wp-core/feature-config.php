<?php
//COPYRIGHT � SkyBlow Company VATIN: PL5170154130 www.it.skyblow.com, www.ofeatures.com

if (!function_exists('ofeatures_config_page')){
    function ofeatures_config_page($ofeatures_configuration_title
        , $ofeatures_configuration_featuretype
        , $ofeatures_configuration_features_title
        , $ofeatures_configuration_no_features_info
        , $plugin_path
        , $ofeatures_url) { 
        
            //Removing duplicates from ofeatures_footer_excludedpages
            $ofeatures_footer_excludedpages = get_option('ofeatures_footer_excludedpages', '');
            $ofeatures_footer_excludedpages = explode(',', $ofeatures_footer_excludedpages);
            $ofeatures_footer_excludedpages = array_unique($ofeatures_footer_excludedpages);
            $ofeatures_footer_excludedpages = array_filter($ofeatures_footer_excludedpages);
            $ofeatures_footer_excludedpages = implode(',', $ofeatures_footer_excludedpages);
            update_option('ofeatures_footer_excludedpages', $ofeatures_footer_excludedpages);
        ?>
        
        <br/><br/>
        <img style="width:64px; margin-left:10px;" alt="" src="<?php echo $plugin_path;?>/ofeatures-wp-core/img/ofeatures-logo-128px128px.png" />
        <form  class="ofeatures-form" autocomplete="off" method="post" action="options.php">
            <div>
                <h1 class="ofeatures-config-title"><?php echo $ofeatures_configuration_title;?>  
                <br/>
                <?php wp_nonce_field('update-options'); ?>
                <h3 class="details-request" style=""><?php _e("Please provide the plugin access data. You can find it in your oFeatures account in Menu > Settings > Plugins")?>
                <?php if (!get_option('ofeatures_clientid')){ ?> 
                    <br/><br/>
                    <span class="no-account-question"><?php _e("Don't Have an Account?")?> <a target="_blank" style="cursor:pointer" href="http://ofeatures.com?utm_source=cms&utm_medium=button-main&utm_campaign=wordpress-plugin"><span style="text-decoration:underline;"><?php _e('Create oFeatures account') ?></span> <i class="fa fa-arrow-circle-right"></i></a></span>
                <?php } ?>  
                
                </h3>
              
                <table>
                    <tr>
                        <td><?php _e("Client id")?></td>
                        <td><input placeholder='<?php _e("oFeatures client id")?>' name="ofeatures_clientid" type="text" id="ofeatures_clientid"
                       value="<?php echo get_option('ofeatures_clientid'); ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php _e("Wordpress token")?></td>
                        <td><input placeholder='<?php _e("wordpress token")?>' name="ofeatures_wptoken" type="password" id="ofeatures_wptoken"
                       value="<?php echo get_option('ofeatures_wptoken'); ?>" />
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="ofeatures_clientid,ofeatures_wptoken,ofeatures_footer,ofeatures_footer_excludedpages" />
                <input type="submit" class='button button-default' value="<?php _e('Save') ?>" />
                <br/>
                <br/>
            </div>
            <h4 class="ok-status" style="display:none">
                <span class="text">
                   <?php _e(" All features have been synchronized.")?> <i class="fa fa-check-circle"></i>  
                   <span onclick="location.reload()" style="font-size:10px; font-weight: normal; text-decoration: underline; color:#777; cursor:pointer; margin-left:10px"><?php _e('synchronize again') ?></span>
                   <br/><b>
                    <?php _e("Every time you change something in your features (for example, the style) please visit this configuration page again to keep your features updated.")?> </b>
                    <br/><br/> 
                    <h3><?php echo $ofeatures_configuration_features_title;?></h3>
                </span>
            </h4>
            <div class="features"></div>
            <?php if (get_option('ofeatures_clientid')){ ?> 
                <h4 class="preloader"><span class="text"><?php _e("Synchronizing your features...")?></span><br/>
                    <img style="" alt="" src="<?php echo $plugin_path;?>/ofeatures-wp-core/img/preloader.gif" />
                </h4>
                <script>
                    synchronize("<?php echo $ofeatures_configuration_no_features_info;?>",
                    "<?php echo get_current_domain_no_protocol(); ?>", "<?php echo $ofeatures_configuration_featuretype;?>");
                </script>
            <?php } ?>
            <br/>
            <h3><?php _e("Don't Have an Account?")?></h3>
            <a class='button button-primary' target="_blank" href="http://ofeatures.com?utm_source=cms&utm_medium=button-below&utm_campaign=wordpress-plugin"><?php _e('Create oFeatures account') ?></a>
            <br/><br/><br/> 
            <span class="button button-default show-every-page-button" onclick="scrollToEveryPage()">Content on all pages <i class="fa fa-angle-down"></i></span>
            <div class="every-place-block" style="display:none" >
                <div class="every-place-content">
                    <h4 style="margin-bottom:5px;"><?php _e(" If your feature is floating paste the Shortcode in this box. Otherwise, paste the code directly to page content.")?></h4>
                    <textarea placeholder='<?php _e("To add the feature to all pages you can paste the featutre Shortcode in here.")?>' name="ofeatures_footer" id="ofeatures_footer"><?php echo get_option('ofeatures_footer'); ?></textarea>
                    <br/><br/><?php _e('Exclude pages/posts') ?>:<br/>
                    <textarea style="max-width:300px; max-height:50px;" class='excluded-page-ids' placeholder='<?php _e("Comma separated page/post ids")?>' name="ofeatures_footer_excludedpages" id="ofeatures_footer_excludedpages"><?php echo get_option('ofeatures_footer_excludedpages', '')?></textarea>
                    &nbsp;&nbsp; <input type="submit" class='button button-default' value="<?php _e('Exclude') ?>" />
                    <?php
                        $excuded_page_ids = get_option('ofeatures_footer_excludedpages', '');
                        $excuded_page_ids = explode(',', $excuded_page_ids);
                        $excuded_page_ids = array_filter($excuded_page_ids);
                        
                        if (count($excuded_page_ids) > 0){
                            ?>
                                <br/>
                                <br/>
                                <?php _e('Excluded') ?>:<br/><br/>
                            <?php
                        }
                        
                        foreach($excuded_page_ids as $id){
                            $title = get_the_title($id);
                            if ($title == null)
                                $title = "(no title or doesn't exist)";
                            echo "<span class='excluded-page-box' excludedid='$id'>$title ($id) <i  onclick='remove_excluded($id)' class='fa fa-times'></i></span>";
                        }
                    ?>
                     <br/><br/>
                    <input type="submit" class='button button-default' value="<?php _e('Save') ?>" />
                </div>
            </div>
            <br/><br/>
            <br/><br/>
            <?php echo (_e("You can contact us at any time to get support, leave feedback, request feature or functionality:")) ?><br/><br/>
            - <?php echo (_e("Via Skype")) ?>: <a href="skype:ofeatures.support?add">ofeatures.support</a><br/>
            - <?php echo (_e("On our website")) ?>: <a target="_blank" href="http://ofeatures.com">ofeatures.com</a>  
        </form>

        
        <?php
    }
}
?>