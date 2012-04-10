<?php
class SharexyAdmin extends SharexyMain {
    private $adminMenu;
    private $errorReporter = null;

    public function __construct() {
        parent::__construct();
        $this->adminMenu = array(
            array(
                'top' => array(
                    "parent_slug" => "options-general.php" ,
                    "page_title" => "Sharexy Settings",
                    "menu_title" => "Sharexy",
                    "capability" => "manage_options",
                    "menu_slug" => "sharexy-menu",
                    "function" => array(&$this, 'buttonsSettings'),
                    "icon_url" => $this->params['logo']['small_img'] ? $this->params['logo']['path'] . $this->params['logo']['small_img'] : '',
                    "position" => NULL
                )
//                ,'sub' => array(
//                    array(
//                        "parent_slug" => "sharexy-menu" ,
//                        "page_title" => "",
//                        "menu_title" => "",
//                        "capability" => "manage_options",
//                        "menu_slug" => "sharexy-menu-something",
//                        "function" => array(&$this, 'constructorMainTpl')
//                    ),
//                    array(
//                        "parent_slug" => "sharexy-menu" ,
//                        "page_title" => "",
//                        "menu_title" => "",
//                        "capability" => "manage_options",
//                        "menu_slug" => "sharexy-menu-something",
//                        "function" => array(&$this, 'constructorMainTpl')
//                    )
//                )
            )
        );

    }

    public function setErrorObject(SharexyErrorReporter $errorReporter) {
        $this->errorReporter = $errorReporter;
    }

    public function composeMenuBar() {
        if (!is_array($this->adminMenu) || !(count($this->adminMenu) > 0)) {
            return;
        }
        $menuPagesCount = count($this->adminMenu);
        for ($i = 0; $i < $menuPagesCount; $i++) {
            $topMenu = isset($this->adminMenu[$i]['top']) ? $this->adminMenu[$i]['top'] : false;
            if (!is_array($topMenu) || !(count($topMenu) > 0)) {
                continue;
            }
            $page_title = isset($topMenu['page_title']) ? $topMenu['page_title'] : '';
            $menu_title = isset($topMenu['menu_title']) ? $topMenu['menu_title'] : '';
            $capability = isset($topMenu['capability']) ? $topMenu['capability'] : '';
            $menu_slug = isset($topMenu['menu_slug']) ? $topMenu['menu_slug'] : '';
            $function = isset($topMenu['function']) ? $topMenu['function'] : '';
            $icon_url = isset($topMenu['icon_url']) ? $topMenu['icon_url'] : '';
            $position = isset($topMenu['position']) ? $topMenu['position'] : NULL;
            $parent_slug = isset($topMenu['parent_slug']) ? $topMenu['parent_slug'] : false;
            if ($parent_slug) {
                add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
            } else {
                add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
            }
            $subMenu = isset($this->adminMenu[$i]['sub']) ? $this->adminMenu[$i]['sub'] : false;
            if (!is_array($subMenu) || !(count($subMenu) > 0)) {
                continue;
            }
            $menuSubPagesCount = count($subMenu);
            for ($j = 0; $j < $menuSubPagesCount; $j++) {
                if (!is_array($subMenu[$j]) || !(count($subMenu[$j]) > 0)) {
                    continue;
                }
                $parent_slug = isset($subMenu[$j]['parent_slug']) ? $subMenu[$j]['parent_slug'] : '';
                $page_title = isset($subMenu[$j]['page_title']) ? $subMenu[$j]['page_title'] : '';
                $menu_title = isset($subMenu[$j]['menu_title']) ? $subMenu[$j]['menu_title'] : '';
                $capability = isset($subMenu[$j]['capability']) ? $subMenu[$j]['capability'] : '';
                $menu_slug = isset($subMenu[$j]['menu_slug']) ? $subMenu[$j]['menu_slug'] : '';
                $function = isset($subMenu[$j]['function']) ? $subMenu[$j]['function'] : '';
                add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
            }
        }
    }

    public function initMenu() {
        add_action('admin_menu', array(&$this, 'composeMenuBar'), 1);


    }
    public function registerAdminScripts() {
       /* wp_register_script('sharexy_plugin_nicecheckbox_js', WP_PLUGIN_URL . '/sharexy/js/nicecheckbox.js');
        wp_register_script('sharexy_plugin_niceradio_js', WP_PLUGIN_URL . '/sharexy/js/niceradio.js');
        wp_register_script('sharexy_plugin_constructor_js', WP_PLUGIN_URL . '/sharexy/js/constructor.js');
        wp_register_script('sharexy_plugin_tipsy_js', WP_PLUGIN_URL . '/sharexy/js/tipsy/jquery.tipsy.js');
        wp_register_script('sharexy_plugin_tool-man_core_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/core.js');
        wp_register_script('sharexy_plugin_tool-man_events_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/events.js');
        wp_register_script('sharexy_plugin_tool-man_css_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/css.js');
        wp_register_script('sharexy_plugin_tool-man_coordinates_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/coordinates.js');
        wp_register_script('sharexy_plugin_tool-man_drag_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/drag.js');
        wp_register_script('sharexy_plugin_tool-man_dragsort_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/dragsort.js');
        wp_register_script('sharexy_plugin_tool-man_cookies_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/cookies.js');
        wp_register_script('sharexy_plugin_tool-man_index_js', WP_PLUGIN_URL . '/sharexy/js/tool-man/index.js');
        wp_register_script('sharexy_plugin_farbtastic_js', WP_PLUGIN_URL . '/sharexy/js/farbtastic/farbtastic.js');
        wp_register_script('sharexy_plugin_buzz_js', WP_PLUGIN_URL . '/sharexy/js/buzz.js');
*/
    }
    public function registerAdminCSS() {
  /*      wp_register_style('sharexy_plugin_constructor_css_farbtastic', WP_PLUGIN_URL . '/sharexy/js/farbtastic/farbtastic.css');
        wp_register_style('sharexy_plugin_constructor_css_tipsy', WP_PLUGIN_URL . '/sharexy/css/tipsy.css');
        wp_register_style('sharexy_plugin_constructor_css', WP_PLUGIN_URL . '/sharexy/css/constructor.css');
*/
    }
    public function buttonsSettings() {
        if (!is_user_logged_in() || !is_admin() ) {
            wp_die('hacking??');
            return;
        }
        if (isset($_REQUEST['sel']) && trim($_REQUEST['sel']) === 'sharexy_save_style_data') {
            $this->saveParams($_REQUEST);
            echo "ready";
            unset($_REQUEST);
        } else {
            $this->constructorMainTpl();
        }
    }
    private function validateServicesList($list) {
        $servicesArr = array();
        if (strlen( trim( $list ) ) > 0) {
            $servicesTmpArr = explode(',', $list);
            for ($i = 0; $i < count($servicesTmpArr); $i++) {
                if (strlen(trim($servicesTmpArr[$i])) > 0) {
                    $servicesArr[$servicesTmpArr[$i]] = 1;
                }
            }
            $servicesArr = array_keys($servicesArr);
        }
        return $servicesArr;
    }

    private function saveParams($data) {
        if (!is_array($data) || !(count($data) > 0) ) {
            return;
        }
        $newParams = array();
        $newParams['user_id'] = isset($data['user_id']) ? $this->validateWebmasterId($data['user_id']) : 0;
        if (!$newParams['user_id']) {
            $newParams['allways_show_ads'] = 0;
            $newParams['show_ads_sharing'] = 0;
            $newParams['show_ads_cursor'] = 0;
        } else {
            $newParams['allways_show_ads'] = isset($data['allways_show_ads']) ? intval($data['allways_show_ads']) : 0;
            $newParams['show_ads_sharing'] = isset($data['show_ads_sharing']) ? intval($data['show_ads_sharing']) : 0;
            $newParams['show_ads_cursor'] = isset($data['show_ads_cursor']) ? intval($data['show_ads_cursor']) : 0;
        }
        $newParams['popup_bot_a'] = isset($data['popup_bot_a']) ? intval($data['popup_bot_a']) : 0;
        $newParams['design'] = isset($data['design']) && $data['design'] ? trim($data['design']) : $newParams['design'];
        update_option($this->adminOptionsName, serialize($newParams));

        $placements = $this->getPlacements();
        $placementsParams = array();
        foreach ($placements as $place => $params) {
            $placementsParams[$place]['display'] = isset($data['placement_' . $place]) && intval($data['placement_' . $place]) > 0 ? 1 : 0;
            $currentPlaceStyleParams = array();
            if (!$placementsParams[$place]['display']) {
                continue;
            }
            //params
            if (isset( $data['pages_mode_front_' . $place] ) && isset( $data['pages_mode_page_' . $place] )) {
                $placementsParams[$place]['pages_mode'] = array(
                    'front' => intval($data['pages_mode_front_' . $place]) > 0 ? 1 : 0,
                    'page' => intval($data['pages_mode_page_' . $place]) > 0 ? 1 : 0,
                );
            }
            if (isset( $data['align_' . $place] ) && in_array(trim($data['align_' . $place]), array('left', 'right', 'center'))) {
                $placementsParams[$place]['align'] = trim($data['align_' . $place]);
            }
            if (isset( $data['show_ads_' . $place] )) {
                $placementsParams[$place]['show_ads'] = intval($data['show_ads_' . $place]) > 0 && (
                    $newParams['allways_show_ads'] ||
                    $newParams['show_ads_sharing'] ||
                    $newParams['show_ads_cursor']
                ) ? 1 : 0 ;
                if ($placementsParams[$place]['show_ads'] === 0) {
                    $currentPlaceStyleParams['allways_show_ads'] = 0;
                    $currentPlaceStyleParams['show_ads_sharing'] = 0;
                    $currentPlaceStyleParams['show_ads_cursor'] = 0;
                }
            }
            //style
            $currentPlaceStyleParams['services'] = isset( $data['services_' . $place] ) ? $this->validateServicesList( $data['services_' . $place] ) : array();
            if (isset( $data['size_float_' . $place] )) {
                $currentPlaceStyleParams['size_float'] = $data['size_float_' . $place];
            }
            if (isset( $data['size_static_' . $place] )) {
                $currentPlaceStyleParams['size_static'] = $data['size_static_' . $place];
            }
            if (isset( $data['retweet@username_' . $place] )) {
                $currentPlaceStyleParams['retweet@username'] = $data['retweet@username_' . $place];
            }
            if (isset( $data['buzz_' . $place] )) {
                $currentPlaceStyleParams['buzz'] = $data['buzz_' . $place];
            }
            if (isset( $data['counters_float_' . $place] )) {
                $currentPlaceStyleParams['counters_float'] = $data['counters_float_' . $place];
            }
            if (isset( $data['counters_static_' . $place] ) ) {
                $currentPlaceStyleParams['counters'] = $data['counters_static_' . $place];
            }
            if (isset( $data['counters_' . $place] )) {
                $currentPlaceStyleParams['counters'] = $data['counters_' . $place];
            }
            if (isset( $data['bg_float_' . $place] )) {
                $currentPlaceStyleParams['bg_float'] = $data['bg_float_' . $place];
            }
            if (isset( $data['bg_color_' . $place] )) {
                $currentPlaceStyleParams['bg_color'] = $data['bg_color_' . $place];
            }
            if (isset( $data['mode_float_' . $place] )) {
                $currentPlaceStyleParams['mode_float'] = $data['mode_float_' . $place];
            }

            update_option($this->adminOptionsName . '_placement_' . $place . '_style', serialize($currentPlaceStyleParams));
        }
        update_option($this->adminOptionsName . '_placements', serialize($placementsParams));
    }

    private function constructorMainTpl() {
       /* not work in wordpress 3.0!!
        *  wp_enqueue_script('jquery');
        wp_enqueue_script('sharexy_plugin_tipsy_js');
        wp_enqueue_script('sharexy_plugin_tool-man_core_js');
        wp_enqueue_script('sharexy_plugin_tool-man_events_js');
        wp_enqueue_script('sharexy_plugin_tool-man_css_js');
        wp_enqueue_script('sharexy_plugin_tool-man_coordinates_js');
        wp_enqueue_script('sharexy_plugin_tool-man_drag_js');
        wp_enqueue_script('sharexy_plugin_tool-man_dragsort_js');
        wp_enqueue_script('sharexy_plugin_tool-man_cookies_js');
        wp_enqueue_script('sharexy_plugin_tool-man_index_js');
        wp_enqueue_script('sharexy_plugin_farbtastic_js');
        wp_enqueue_script('sharexy_plugin_nicecheckbox_js');
        wp_enqueue_script('sharexy_plugin_niceradio_js');
        wp_enqueue_script('sharexy_plugin_buzz_js');
        wp_enqueue_style('sharexy_plugin_constructor_css_farbtastic');
        wp_enqueue_style('sharexy_plugin_constructor_css_tipsy');

        wp_enqueue_script('sharexy_plugin_constructor_js');
        wp_enqueue_style('sharexy_plugin_constructor_css');
       */ $designs = $this->getDesignNames();
        $socSources = $this->getSocialSourcesNames();
        $placements = $this->getPlacements();
        $styleParams = $this->getStyle();
        $placementsParams = array();
        if (!empty( $placements )) {
            foreach ($placements as $place => $params) {
                $placementsParams[$place]['params'] = $params;
                $style = $this->getPlacementsStyleParams($place);
                $placementsParams[$place]['style'] = $this->mixMainPlaceStyleParams($styleParams, $style);
            }
        }
        $logoSRC = $this->params['logo']['big_img'] ? $this->params['logo']['path'] . $this->params['logo']['big_img'] : '';
        $imgPath = $this->params['server']['imgPath'];
        $scriptPath = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] .'/'.$this->params['server']['scriptPath'];
        include "templates/constructor.phtml";
    }

    private function getSocialSourcesNames() {
        $sources = array();
        $response = wp_remote_get( $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] . "/" . $this->params['server']['socialSourcesTXT']  );
        if ( !is_wp_error( $response ) ) {
            $sources = isset($response['body']) ? json_decode( $response['body'] ) : $sources;
        }
        return $sources;
    }

    private function getDesignNames() {
        $designs = array();
        $response = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] . "/" . $this->params['server']['stylesTXT'];
        $initInfo  = file_get_contents($response);
        $initInfo  = json_decode($initInfo);
        $iteration = 0;
        foreach($initInfo as $entry=>$init)
        {
              $designs[$iteration] = array(
                'id' => $entry,
                'name' => ucwords($entry),
                'url' => $init->url
            );
            $iteration++;
        }
        return $designs;
    }
}