<?php
abstract class SharexyMain {
    protected $params;
    protected $adminOptionsName;
    protected  $defaultWidgetParams;
    protected  $defaultPlacements;
    protected  $defaultPlacementsStyleParams;

    public function __construct() {
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
            )
        );
        $this->defaultPlacements = array(
            'top_post' => array(
                "display" => 0,
                'pages_mode' => array('front' => 0, "page" => 0, "post" => 1 ),
                'align' => 'right',
                'show_ads' => 1
            ),
            'top' => array(
                "display" => 0,
                'pages_mode' => array('front' => 1, "page" => 1, "post" => 0 ),
                'align' => 'right',
                'show_ads' => 1
            ),
            'bottom_post' => array(
                "display" => 1,
                'pages_mode' => array('front' => 0, "page" => 0, "post" => 1 ),
                'align' => 'right',
                'show_ads' => 1
            ),
            'bottom' => array(
                "display" => 1,
                'pages_mode' => array('front' => 1, "page" => 1, "post" => 0 ),
                'align' => 'right',
                'show_ads' => 1
            ),
            'float' => array(
                "display" => 0,
                'pages_mode' => array('front' => 1, "page" => 1, "post" => 1 ),
                'align' => 'right',
                'show_ads' => 1
            )
        );
        $this->defaultPlacementsStyleParams = array(
            'bottom' => array(
                'layout_static' => 'h',
                'type' => 'st',
                'size_static' => '32',
                'buzz' => '1',
                'labels' => '',
                'counters' => ''
            ),
            'bottom_post' => array(),
            'top' => array(),
            'top_post' => array(),
            'float' => array(
                'type' => 'f',
                'mode_float' => 'l'
            )
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
            'retweet@username' => 'retweetmeme'
        );
    }

    protected function getPlacements() {
        $defaultPlacements = $this->defaultPlacements;
        $placements = get_option( $this->adminOptionsName . '_placements' );
        if (!$placements) {
            return $defaultPlacements;
        }
        $placements = unserialize($placements);
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

    protected function getStyle() {
        $resultParams = $this->defaultWidgetParams;
        $savedParams = get_option( $this->adminOptionsName );
        if ( !$savedParams ) {
            return $resultParams;
        }
        $savedParams = unserialize( $savedParams );
        foreach ($resultParams as $key => $value) {
            if ( isset($savedParams[$key]) ) {
                $resultParams[$key] = $savedParams[$key];
            }
        }
        return $resultParams;
    }


    protected function getPlacementsStyleParams($place) {
        $defaultStyleParams = isset($this->defaultPlacementsStyleParams[$place]) ? $this->defaultPlacementsStyleParams[$place] : array();
        $placeStyleParams = get_option( $this->adminOptionsName . '_placement_' . $place . '_style' );
        $placeStyleParams = $placeStyleParams ? unserialize( $placeStyleParams ) : array();
        if ( empty( $placeStyleParams ) ) {
            return $defaultStyleParams;
        }
        if ( empty( $defaultStyleParams ) ) {
            return $placeStyleParams;
        }
        foreach ($defaultStyleParams as $key => $value) {
            $placeStyleParams[$key] = isset($placeStyleParams[$key]) ? $placeStyleParams[$key] : $value;
        }
        return $placeStyleParams;
    }

    protected function mixMainPlaceStyleParams(array $mainParams, array $placeParams) {
        if (empty($mainParams) || empty($placeParams)) {
            return $mainParams;
        }
        foreach ($mainParams as $key => $value) {
            $mainParams[$key] = isset($placeParams[$key]) ? $placeParams[$key] : $value;
        }
        return $mainParams;
    }

    protected function validateWebmasterId($webmasterId) {
        $fail = 0;
        if (is_string($webmasterId) && $webmasterId && strlen(trim($webmasterId)) > 0) {
            $webmasterId = trim($webmasterId);
            $wmArr = explode('-', $webmasterId);
            if (count($wmArr) !== 2 || strlen($wmArr[0]) !== 3 || !(intval($wmArr[1]) >= 0) ) {
                $webmasterId = $fail;
            }
        } else {
            $webmasterId = $fail;
        }
        return $webmasterId;
    }

}
