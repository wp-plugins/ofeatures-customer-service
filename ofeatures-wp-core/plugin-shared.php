<?php
//COPYRIGHT � SkyBlow Company VATIN: PL5170154130 www.it.skyblow.com, www.ofeatures.com

if (!function_exists('get_ofeatures_plugins_remained')) {
    
    //Scripts and styles
    function add_ofeatures_scripts() {
        global $plugin_name;
        wp_enqueue_script('ofeatures-wp', plugins_url() . "/$plugin_name/ofeatures-wp-core/js/ofeatures-wp.js", array(), '1.3.0');
        wp_enqueue_style('ofeatures-wp', plugins_url() . "/$plugin_name/ofeatures-wp-core/css/ofeatures-wp.css", array(), '1.3.0');
        wp_enqueue_style('font-awesome', 'https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3');
    }

    add_action('admin_enqueue_scripts', 'add_ofeatures_scripts');
    
    function delete_ofeatures_options() {
        delete_option('ofeatures_features');
        delete_option('ofeatures_clientid');
        delete_option('ofeatures_wptoken');
        delete_option('ofeatures_footer');
        delete_option('ofeatures_footer_excludedpages');
    }

    function add_ofeatures_options() {
        add_option("ofeatures_features", array(), '', 'yes');
        add_option("ofeatures_clientid", '', '', 'yes');
        add_option("ofeatures_wptoken", '', '', 'yes');
        add_option("ofeatures_footer", '', '', 'yes');
        //Comma separated indices
        add_option("ofeatures_footer_excludedpages", '', '', 'yes');   
    }

    //Support panel
    function ofeatures_register_support_panel() {
        if (is_admin() && !function_exists('ofeatures_support_panel_menu')) {
            add_action('admin_menu', 'ofeatures_support_panel_menu');

            function ofeatures_support_panel_menu() {
                global $plugin_name;
                add_menu_page('oFeatures Support Panel', 'Support Panel<br/>oFeatures', 'administrator', 'support-panel-ofeatures'
                    , 'support_panel_ofeatures_html_page', plugins_url() . "/$plugin_name/ofeatures-wp-core/img/ofeatures-logo-small.png");
            }

        }
    }

    //Footer 
    $ofeatures_footer = get_option('ofeatures_footer');
    if (!empty($ofeatures_footer)) {

        function ofeatures_footer() {
            $excuded_page_ids = get_option('ofeatures_footer_excludedpages', '');
            $excuded_page_ids = explode(',', $excuded_page_ids);
            $excuded_page_ids = array_filter($excuded_page_ids);
            
            global $post;
            $current_page_id = $post->ID;
            
            if (!in_array($current_page_id, $excuded_page_ids))
                echo do_shortcode(get_option('ofeatures_footer'));
        }

        add_action('wp_footer', 'ofeatures_footer');
    }

    //Shortcode
    function ofeature($attributes) {
        $featureId = $attributes['id'];
        $languageId = $attributes['lang'];
        $ofeatures_features = get_option('ofeatures_features');
        if (is_array($ofeatures_features) && isset($ofeatures_features[$featureId]) && isset($ofeatures_features[$featureId][$languageId])) {
            return $ofeatures_features[$featureId][$languageId];
        }
        return "[]";
    }

    add_shortcode('ofeature', 'ofeature');

    //Utils
    function get_current_domain_no_protocol() {
        return str_replace('www.', '', $_SERVER['SERVER_NAME']);
    }

    //Ajax synchronize action
    function synchronize_ofeatures() {
        $clientId = get_option('ofeatures_clientid');
        if (isset($clientId)) {
            $baseUrl = "https://" . $clientId . ".panel.ofeatures.com";
            //$plugintype = $_POST["plugintype"];
            $wpToken = get_option('ofeatures_wptoken');
            $ofeatures_features = get_option('ofeatures_features');
            $response = @file_get_contents($baseUrl . '/skyblow.clientbackend/pluginaccess/features?plugintechnology=wp'
                . '&websiteaddress='
                . get_current_domain_no_protocol()
                . '&wpToken='
                . $wpToken);

            if ($response != "no-access-rights" && $response !== false) {
                $result = json_decode($response, true);
                $featuresTemp = $result['features'];
                $languagesTemp = $result['languages'];

                if (count($featuresTemp) == 0) {
                    echo "no-features-found";
                    die();
                }

                ?>  
                <div class="container">       
                    <?php
                    foreach ($featuresTemp as $feature) {
                        $codes = $feature['codes'];
                        $featureId = $feature['id'];
                        $ofeatures_features[$featureId] = $codes;
                        $languageIndex = 0;
                        foreach ($languagesTemp as $language) { 
                            $languageIndex++;
                            ?>
                            <div style="<?php echo ($languageIndex > 1 ? "display:none" : ""); ?>" class="feature-block language-index-<?php echo $languageIndex;?> feature-index-<?php echo $featureId;?>">
                                <h2 class="feature-title"><i class='<?php echo $feature['icon']; ?>'></i> <?php echo $feature['name']; ?> - <?php echo $language['name']; ?> Language
                                    <?php 
                                        if ($languageIndex == 1){ 
                                          echo "<span class=\"show-other-languages-button\" onclick=\"showLanguages(this, $languageIndex, '$featureId')\">show other languges <i class=\"fa fa-angle-down\"></i></span>";
                                        }
                                     ?>
                                </h2>
                                <h4 class="add-instruction" >To add this <?php echo $feature['typehumanreadable']; ?> to your page please copy and paste this Shortcode:</h4>
                                <div class="code">[ofeature name="<?php echo $feature['name']; ?>" id="<?php echo $feature['id']; ?>" lang="<?php echo $language['id']; ?>"]</div>
                                <a class="button-default" style='float:right' href="#" onclick='scrollToEveryPage()'>put this <?php echo $feature['typehumanreadable']; ?> on all pages</a>
                            </div>
                            
                    <?php
                        }
                    } ?>  
                </div> 
                <?php 
                    update_option('ofeatures_features', $ofeatures_features);
                } else {
                    echo "no-access-rights";
                }
        } else {
            echo "false";
        }
        die();
    }

    add_action('wp_ajax_synchronize_ofeatures', 'synchronize_ofeatures');
}

?>