<?php

/*
    Copyright 2012  sharexy.com  (email: support@sharexy.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class TrafficMiner extends SharexyMain{

	function TrafficMiner()
	{
		$this->parentInit();
		$this->global_params = parent::getStyle();
		
		
		$this->adminOptionsName = 'SharexyTM';
			
		/*
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
                'imgPath' => WP_PLUGIN_URL . '/sharexy-tm/img/'
            ),
            'logo' => array(
                'path' => WP_PLUGIN_URL . '/sharexy-tm/img/',
                'big_img' => 'logo.png',
                'small_img' => 'favicon.png'
            )
        );
		*/
		
		$this->menu_page_params = array(
				"parent_slug" => "sharexy-menu" ,  
				"page_title" => "TrafficMiner widget",
				"menu_title" => "TrafficMiner widget",
				"capability" => "manage_options",
				"menu_slug" => "sharexy-widget-menu",
				"function" => array(&$this, 'widgetSettings'),
				"icon_url" => $this->params['logo']['small_img'] ? $this->params['logo']['path'] . $this->params['logo']['small_img'] : '',
			);

		
		
		$this->defaultWidgetParams = array(
            'user_id' => $this->global_params['user_id'],
            'popup_bot_a' => '0',
            'show_ads' => '1',
			"tm_place_home" => '0', 
			"tm_place_single" => '0', 
			"tm_place_page" => '0', 
			"tm_place_archive" => '0', 
			"tm_place_attachment" => '0', 
			"tm_place_excerpt" => '0', 
			"tm_bg_color" => '#fff', 
			"tm_widget_width" => '600', 
			"tm_label" => 'You might also like:', 
			"tm_hide_temp_message" => '0', 
        );

	}

	function widgetSettings() 
	{
		if (!is_user_logged_in() || !is_admin() ) 
		{
			wp_die('hacking??');
			return;
		}
		if (isset($_REQUEST['sel']) && trim($_REQUEST['sel']) === 'sharexy_save_trafficminer_data') 
		{
			$this->saveParams($_REQUEST);
			echo "ready";
			unset($_REQUEST);
			// $this->constructorMainTpl();
		} 
		else 
		{
			$this->constructorMainTpl();
		}
	}

	
	function constructorMainTpl()
	{
		$styleParams = $this->getStyle();
		
		$logoSRC = $this->params['logo']['big_img'] ? $this->params['logo']['path'] . $this->params['logo']['big_img'] : '';
        $imgPath = $this->params['server']['imgPath'];
        $scriptPath = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] .'/'.$this->params['server']['scriptPath'];
		
		include "templates/constructor_tm.phtml";
	}
	
	function getStyle() {

        $resultParams = $this->defaultWidgetParams;
        $savedParams = get_option( $this->adminOptionsName );
		
        if ($savedParams && is_string($savedParams)) {
            $savedParams = @unserialize( $savedParams );
        } elseif (!$savedParams || !is_array($savedParams) || empty($savedParams)) {
            return $resultParams;
        }
		
		// $savedParams[$key]
		
        foreach ($resultParams as $key => $value) {
            if ( isset($savedParams[$key]) ) {
                $resultParams[$key] = $savedParams[$key];
            }
        }
        return $resultParams;
    }


	function composeMenuBar()
	{
		$topMenu = $this->menu_page_params;
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
	}
	
	function saveParams($data = false) 
	{
		if(!$data)
		{
			$data = $this->defaultWidgetParams;
		}
		
        if (!is_array($data) || !(count($data) > 0) ) {
            return;
        }
		$old_params = $this->getStyle();
		

        $data['user_id'] = isset($data['user_id']) ? $this->validateWebmasterId($data['user_id']) : 0;
        $data['tm_label'] = isset($data['tm_label']) ? wp_kses($data['tm_label'], array()) : '';
        $data['tm_bg_color'] = isset($data['tm_bg_color']) ? $data['tm_bg_color'] : '#fff';
        $data['tm_bg_color'] = preg_match('/none|\#fff/i', $data['tm_bg_color']) ? $data['tm_bg_color']  : '#fff';
		$data['tm_widget_width'] = intval($data['tm_widget_width']) > 0 ? intval($data['tm_widget_width']) : 600;
		$data['tm_widget_width'] = $data['tm_widget_width'] < 600 ? 600 : $data['tm_widget_width'];

		$placement_options = array(
			"tm_place_home", "tm_place_single", "tm_place_page", "tm_place_archive", "tm_place_attachment", "tm_place_excerpt", 
		);
		foreach($placement_options as $po)
		{
			if(isset($data[$po]))
			{
				$data[$po] = intval($data[$po]);
			}else{
				$data[$po] = 0;
			}
		}

        update_option($this->adminOptionsName, serialize($data));
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

	function tempolar_admin_notice(){ 
		if( get_option('tempolar_admin_notice_hide' ) ) {
			return;
		} 

		$blogurl = get_bloginfo( 'wpurl' ); 
		echo '<div class="updated" id="tempolar_admin_notice" style="position:relative;">
		   <p>Hello! Our team is proud to present an updated version of Sharexy plugin - now with a possibility of content distribution via TrafficMiner - it\'s intended for everyone looking to maximize their content exposure on the web and, in turn, drive more targeted traffic your way. Now Sharexy is capable of distributing your best content at relevant and socially visible pages so that you may attract interested visitors, without a need for traffic exchange. Our concept is much more elaborate - it\'s all about making your content work for you everywhere on the web, not just in your blog. </p>
		   
		   <p><a href="http://sharexy.com/about_traffic_miner" target="_blank">Click here for more details on TrafficMiner</a></p>
		   
		   <p><a href="'.$blogurl.'/wp-admin/admin.php?page=sharexy-widget-menu">Configure TrafficMiner right now and increase traffic to your blog!</a> <input type="button" class="button-secondary" value="Hide" title="" id="hide_tempolar_admin_notice" name="hide_tempolar_admin_notice" style="position:absolute; bottom:10px; right:10px;" onclick="hide_ms(); return false;" /> </p>
		   
		   <script type="text/javascript" >
		   function hide_ms(){
				
				jQuery(document).ready(function($) {
					jQuery("#tempolar_admin_notice").hide();
					var data = {
						action: "tempolar_admin_notice_action"
					};
					$.post(ajaxurl, data, function(response) {
					});
				});
			}
			</script>
		</div>';
	}
	
	function tempolar_admin_notice_action_callback() {
			global $wpdb; 
			update_option('tempolar_admin_notice_hide', 1);
			die(); 
		}

	function tm_place($content = '')
	{
		$params = $this->getStyle();
		
		$page_url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else 
		{
			$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		
		if(isset($GLOBALS['post']->ID))
		{
			$post_link = get_permalink($GLOBALS['post']->ID);
		}else{
			$post_link = '';
		}
		
		$blogurl = get_bloginfo('wpurl');
		$unic_id = md5($blogurl . $post_link);

		$text = "<!--traffic miner widget start--><noindex><div id='shr_widget_tminer_".$unic_id."'><script type='text/javascript'>(function(w) { if (!w.TrafficMiner) { w.TrafficMiner = {};} if (!w.TrafficMiner.Params) { w.TrafficMiner.Params = {}; } w.TrafficMiner.Params['tminer_".$unic_id."'] = {'publisher_key':'". $params['user_id'] ."','orientation':'h','background':'". $params['tm_bg_color'] ."','width':'". $params['tm_widget_width'] ."','label':'". $params['tm_label'] ."','ads':'". $params['show_ads'] ."','page_url':'".$post_link."','code_id':'tminer_".$unic_id."'} })(window);</script><script type='text/javascript' src='http://tm.shuttle.sharexy.com/Loader.js'></script></div></noindex><!--traffic miner widget end -->";
		
		if(is_home() AND $params['tm_place_home']) {
			return $content . $text;
		} 
		
		if(is_single() AND $params['tm_place_single'] AND !is_attachment()) {
			return $content . $text;
		}
		
		if(is_page() AND $params['tm_place_page']) {
			return $content . $text;
		} 
		
		if(is_archive() AND $params['tm_place_archive']) { 
			return $content . $text ;
		} 
		
		if(is_attachment() AND $params['tm_place_attachment']) { 
			return $content . $text;
		} 

		// no one match
		return $content ;	
	}

	

	function init() {
		register_activation_hook( __FILE__, array(&$this, 'saveParams') );
		
		add_action('admin_menu', array(&$this, 'composeMenuBar'), 1);
		add_action('admin_notices', array(&$this, 'tempolar_admin_notice') );
		
		add_filter('the_content', array(&$this, 'tm_place') , 20 );
		add_filter('the_excerpt', array(&$this, 'tm_place'), 20 );
		add_action('wp_ajax_tempolar_admin_notice_action', array(&$this, 'tempolar_admin_notice_action_callback') );
    }


}


?>