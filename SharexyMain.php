<?php
class SharexyMain {
    var $params;
    var $adminOptionsName;
    var $defaultWidgetParams;
    var $defaultPlacements;
    var $defaultPlacementsStyleParams;

    function parentInit() {
        $this->adminOptionsName = 'SharexyPluginAdminDisplayMode';
        $this->params = array(
            'server' => array(
                'host' => 'shuttle.sharexy.com',
                'port' => '',
                'protocol' => 'http:',
                'scriptLoader' => 'Loader.js',
                'scriptLoaderLite' => 'LoaderLite.js',
                'scriptPath' => '',
                'stylesTXT' => 'wordpress_data/styles_name.json',
                'socialSourcesTXT' => 'wordpress_data/social_sources_names.json',
                'imgPath' => WP_PLUGIN_URL . '/sharexy/img/'
            ),
            'logo' => array(
                'path' => WP_PLUGIN_URL . '/sharexy/img/',
                'big_img' => 'logo.png',
                'small_img' => 'favicon.png'
            ),
            'sh_server' => array(
                'host' => 'sharexy.com',
                'port' => '',
                'protocol' => 'http:',
                'messagesSource' => 'sharexyroot/message.json',
            )
        );
        $this->defaultPlacements = array(
            'top_post' => array(
                "display" => 0,
                'pages_mode' => array('front' => 0, "page" => 0, "post" => 1 ),
                'align' => 'right',
                'counters_align' => 'none',
                'show_ads' => 1
            ),
            'bottom_post' => array(
                "display" => 1,
                'pages_mode' => array('front' => 0, "page" => 0, "post" => 1 ),
                'align' => 'right',
                'counters_align' => 'none',                
                'show_ads' => 1
            ),
            'top' => array(
                "display" => 0,
                'pages_mode' => array('front' => 1, "page" => 1, "post" => 0 ),
                'align' => 'right',
                'counters_align' => 'none',                
                'show_ads' => 1
            ),
            'bottom' => array(
                "display" => 1,
                'pages_mode' => array('front' => 1, "page" => 1, "post" => 0 ),
                'align' => 'right',
                'counters_align' => 'none',                
                'show_ads' => 1
            ),
            'float' => array(
                "display" => 0,
                'pages_mode' => array('front' => 1, "page" => 1, "post" => 1 ),
                'align' => 'right',
                'counters_align' => 'none',                
                'show_ads' => 1
            ),
            'shortcode' => array(
                "display" => 0,
                'pages_mode' => array('front' => 1, "page" => 1, "post" => 1),
                'align' => '',
                'counters_align' => 'none',                
                'show_ads' => 1
            ),
        );
        $this->defaultPlacementsStyleParams = array(
            'bottom' => array(
                'layout_static' => 'h',
                'type' => 'st',
                'size_static' => '32',
                'buzz' => '1',
                'jumbo_text' => 'Shares',
                'jumbo_color' => '#cccccc',
                'labels' => '',
                'counters' => ''
            ),
            'bottom_post' => array(),
            'top' => array(),
            'top_post' => array(),
            'float' => array(
                'type' => 'f',
                'mode_float' => 'l'
            ),
        	'shortcode' => array(),
        );
        $this->defaultWidgetParams = array(
            'user_id' => '0',
            'design' => 'sharexy',
            'layout_static' => 'h',
            'type' => 'st',
            'mode_float' => 'l',
            'size_float' => '32',
            'size_static' => '32',
            'buzz' => '0',
            'jumbo_text' => 'Shares',
            'jumbo_color' => '#cccccc',
            'services' => array('facebook', 'twitter', 'stumbleupon', 'linkedin'),
            'url' => 'current',
            'allways_show_ads' => '1',
            'show_ads_sharing' => '1',
            'show_ads_cursor' => '1',
            'bg_float' => '0',
            'bg_color' => '#f1f1f1',
            'labels' => '0',
            'counters' => '0',
            'counters_float' => '0',
            'retweet@username' => 'retweetmeme',
            'popup_bot_a' => '0',
            'title_text' => 'Share',
            'title_color' => '#6d7c9e',
            'shorten_links' => 0,
            'bitly_access' => '',
            'bitly_not' => 0,
            'hide_on_mobile_float' => 0, 
            'hide_on_urls' => ''
        );
    }

    function getPlacements() {
        $defaultPlacements = $this->defaultPlacements;
        $placements = get_option( $this->adminOptionsName . '_placements' );
        if ($placements && is_string($placements)) {
            $placements = @unserialize( $placements );
        } elseif (!$placements || !is_array($placements) || empty($placements)) {
            return $defaultPlacements;
        }

        $resultPlacements = array();
        foreach ($defaultPlacements as $place => $params) {            
            $resultPlacements[$place] = array();
            if (empty($params)) {
                continue;
            }
            foreach ($params as $key => $value) {
                $resultPlacements[$place][$key] = isset($placements[$place][$key]) ? $placements[$place][$key] : $value;
            }
        }        
        return $resultPlacements;
    }

    function getStyle() {
        $resultParams = $this->defaultWidgetParams;
        $savedParams = get_option( $this->adminOptionsName );
        if ($savedParams && is_string($savedParams)) {
            $savedParams = @unserialize( $savedParams );
        } elseif (!$savedParams || !is_array($savedParams) || empty($savedParams)) {
            return $resultParams;
        }
        foreach ($resultParams as $key => $value) {
            if ( isset($savedParams[$key]) ) {
                $resultParams[$key] = $savedParams[$key];
            }
        }
        return $resultParams;
    }


    function getPlacementsStyleParams($place) {
        $defaultStyleParams = isset($this->defaultPlacementsStyleParams[$place]) ? $this->defaultPlacementsStyleParams[$place] : array();
        $placeStyleParams = get_option( $this->adminOptionsName . '_placement_' . $place . '_style' );

        if ($placeStyleParams && is_string($placeStyleParams)) {
            $placeStyleParams = @unserialize( $placeStyleParams );
        } elseif (!$placeStyleParams || !is_array($placeStyleParams) || empty($placeStyleParams)) {
            return $defaultStyleParams;
        }        
        foreach ($defaultStyleParams as $key => $value) {
            $placeStyleParams[$key] = isset($placeStyleParams[$key]) ? $placeStyleParams[$key] : $value;
        }
        return $placeStyleParams;        
    }

    function mixMainPlaceStyleParams($mainParams, $placeParams) {
        if (empty($mainParams) || empty($placeParams)) {
            return $mainParams;
        }
        foreach ($mainParams as $key => $value) {
            $mainParams[$key] = isset($placeParams[$key]) ? $placeParams[$key] : $value;
        }
        return $mainParams;
    }

    function validateWebmasterId($webmasterId) {
        $fail = 0;
        if (is_string($webmasterId) && $webmasterId && strlen(trim($webmasterId)) > 0) {
            $webmasterId = preg_match('/^\w{3}-\d{5}$/', trim($webmasterId)) ? strtolower(trim($webmasterId)) : $fail;
        } else {
            $webmasterId = $fail;
        }
        return $webmasterId;
    }

}
