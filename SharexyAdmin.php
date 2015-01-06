<?php
class SharexyAdmin extends SharexyMain {
    var $adminMenu;
    var $errorReporter     = null;
    var $adminMessageText  = ""; 

    function SharexyAdmin() {
        $this->parentInit();
        $this->adminMenu = array(
            array(
                'top' => array(
                    "parent_slug" => "sharexy-menu" ,
                    "page_title" => "Sharexy Settings",
                    "menu_title" => "Sharexy",
                    "capability" => "manage_options",
                    "menu_slug" => "sharexy-menu",
                    "function" => array(&$this, 'buttonsSettings'),
                    "icon_url" => $this->params['logo']['small_img'] ? $this->params['logo']['path'] . $this->params['logo']['small_img'] : '',
                    "position" => NULL
                )
            )
        );
    }

    function setErrorObject($errorReporter) {
        $this->errorReporter = $errorReporter;
    }

    function composeMenuBar() {
        if (!is_array($this->adminMenu) || !(count($this->adminMenu) > 0)) {
            return;
        }
        
        add_menu_page('Sharexy', 'Sharexy', 'manage_options', 'sharexy-menu', array(&$this, 'buttonsSettings'), plugin_dir_url(__FILE__).'img/favicon.png');
        
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

    function initMenu() {
        add_action('admin_menu', array(&$this, 'composeMenuBar'), 1);
    }

    function registerAdminScripts() {}

    function registerAdminCSS() {}

    function buttonsSettings() {
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

    function validateServicesList($list) {
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

    function saveParams($data) {
        if (!is_array($data) || !(count($data) > 0) ) {
            return;
        }
        $newParams = array();
        $newParams['user_id'] = isset($data['user_id']) ? $this->validateWebmasterId($data['user_id']) : 0;
        if (!$newParams['user_id']) {
            $newParams['allways_show_ads'] = 0;
            $newParams['show_ads_sharing'] = 0;
            $newParams['show_ads_cursor']  = 0;
        } else {
            $newParams['allways_show_ads'] = isset($data['allways_show_ads']) ? intval($data['allways_show_ads']) : 0;
            $newParams['show_ads_sharing'] = isset($data['show_ads_sharing']) ? intval($data['show_ads_sharing']) : 0;
            $newParams['show_ads_cursor'] = isset($data['show_ads_cursor']) ? intval($data['show_ads_cursor']) : 0;
        }
        $newParams['popup_bot_a']           = isset($data['popup_bot_a']) ? intval($data['popup_bot_a']) : 0;
        $newParams['title_text']            = isset($data['title_text']) ? $data['title_text'] : '';
        $newParams['title_color']           = isset($data['label_color_float']) ? $data['label_color_float'] : '#6d7c9e';
        $newParams['design']                = isset($data['design']) && $data['design'] ? trim($data['design']) : $newParams['design'];
        $newParams['hide_on_mobile_float']  = isset($data['hide_on_mobile_float']) ? intval($data['hide_on_mobile_float']) : 0;
        $newParams['shorten_links']         = isset($data['shorten_links']) ? intval($data['shorten_links']) : 0;
        $newParams['bitly_not']             = isset($data['bitly_not']) ? intval($data['bitly_not']) : 0;
        $newParams['bitly_access']          = isset($data['bitly_access']) ? $data['bitly_access'] : '';
        $newParams['hide_on_urls']          = isset($data['hide_on_urls']) ? $data['hide_on_urls'] : '';
        update_option($this->adminOptionsName, serialize($newParams));
        if (strlen($newParams['bitly_access'])) {
            add_option("SharexyBitly_".$newParams['bitly_access']);
        }

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
            if (isset( $data['counters_align_' . $place] ) && in_array(trim($data['counters_align_' . $place]), array('none', 'top', 'right', 'bundle', 'jumbo'))) {
                $placementsParams[$place]['counters_align'] = trim($data['counters_align_' . $place]);
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
            if (isset( $data['hide_on_mobile_float_' . $place] )) {
                $currentPlaceStyleParams['hide_on_mobile_float'] = $data['hide_on_mobile_float_' . $place];
            }
            if (isset( $data['mode_float_' . $place] )) {
                $currentPlaceStyleParams['mode_float'] = $data['mode_float_' . $place];
            }            
            if (isset( $data['jumbo_text_' . $place] )) {
                $currentPlaceStyleParams['jumbo_text'] = $data['jumbo_text_' . $place];
            } 
            if (isset( $data['jumbo_color_' . $place] )) {
                $currentPlaceStyleParams['jumbo_color'] = $data['jumbo_color_' . $place];
            }                                   

            update_option($this->adminOptionsName . '_placement_' . $place . '_style', serialize($currentPlaceStyleParams));
        }
        update_option($this->adminOptionsName . '_placements', serialize($placementsParams));
    }

    function constructorMainTpl() {
        $designs = $this->getDesignNames();
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
        $localImgPath = plugin_dir_url(__FILE__);
        if ($styleParams['design'] == 'custom') {
            $scriptPath = plugin_dir_url(__FILE__);            
        } else {
            $scriptPath = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] .'/'.$this->params['server']['scriptPath'];    
        }
        
        include "templates/constructor.phtml";
    }

    function getSocialSourcesNames() {
        $sources = array();                        
        $response = wp_remote_get( $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] . "/" . $this->params['server']['socialSourcesTXT']  );        
        if ( !is_wp_error( $response ) ) {            
            $responseStr = isset($response['body']) ? $response['body'] : '';
            if (!$responseStr) {
                return $sources;
            }
            if (function_exists('json_decode')) {
                $sources = json_decode($responseStr);
            } else {
                $json = new SharexyJson();
                $sources = $json->decode($responseStr);
            }
        }
        return $sources;        
    }

    function getMessage() {
        $message = null;
        $response = wp_remote_get( $this->params['sh_server']['protocol'] . "//" . $this->params['sh_server']['host'] . $this->params['sh_server']['port'] . "/" . $this->params['sh_server']['messagesSource']  );        
        if ( !is_wp_error( $response ) ) {
            $responseStr = isset($response['body']) ? $response['body'] : '';
            if (!$responseStr) {
                return $message;
            }
            if (function_exists('json_decode')) {
                $message = json_decode($responseStr);
            } else {
                $json = new SharexyJson();
                $message = $json->decode($responseStr);
            }
        }
        return $message;        
    }

    function initMessage() {
        $message = $this->getMessage();
        if ($message != null && $this->checkNotify($message->guid)) {
            $this->adminMessageText  = "<script src='".plugin_dir_url(__FILE__)."js/notify.js'>";
            $this->adminMessageText .= "</script>";
            $this->adminMessageText .= "<div id='sharexy_notice' style='background: #fff; box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); padding: 1px 12px; position: relative; z-index: 99999; margin: 10px 10px 0px 0px; box-shadow: 1px 1px 4px rgba(0, 0, 0, .6) !important;'>";
            $this->adminMessageText .= $message->html;
            $this->adminMessageText .= "<div align='right'><button guid='".$message->guid."' onclick='sharexyHideNotifyDialog(this, \"".plugin_dir_url(__FILE__)."ajaxresponder.php\")' style='cursor: pointer;background: #e14d43;border: 0px !important;border-color: #d02a21;color: #fff;min-width: 100px;line-height: 25px;'>Hide</></div>";
            $this->adminMessageText .= "</div>";
            add_action('admin_notices', array(&$this, 'showNotify'));
        }
    } 

    function showNotify() {
        echo $this->adminMessageText;
    }

    function checkNotify($guid) {
        $notifys = get_option("SharexyPlugin_notify");
        if (!$notifys) {
            add_option("SharexyPlugin_notify");
            $notifys = get_option("SharexyPlugin_notify");      
        }
        $notifys = @unserialize($notifys);
        return (!isset($notifys[$guid]) || $notifys[$guid] < 1);
    }

    function getDesignNames() {
        $scriptPath = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] .'/'.$this->params['server']['scriptPath'];
        $designs = array();
        $response = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] . "/" . $this->params['server']['stylesTXT'];
        $initInfo  = file_get_contents($response);
        if (function_exists('json_decode')) {
            $initResult = json_decode($initInfo);
        } else {
            $json = new SharexyJson();
            $initResult = $json->decode($initInfo);
        }
        if (empty($initResult)) {
            return $designs;
        }
        $iteration = 0;
        foreach($initResult as $entry=>$init) {
              $designs[$iteration] = array(
                'id' => $entry,
                'name' => ucwords($entry),
                'url' => $init->url,
                'scriptPath' => $scriptPath
            );
            $iteration++;
        }
        $customTheme['id']          = 'custom';
        $customTheme['name']        = 'Custom Theme';
        $customTheme['url']         = '';
        $customTheme['scriptPath']  = plugin_dir_url(__FILE__);
        array_unshift($designs, $customTheme);        
        return $designs;
    }
}