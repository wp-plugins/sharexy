<?php
class SharexyAdmin extends SharexyMain {
    var $adminMenu;
    var $errorReporter = null;
    var $textNoNews = 'Hey! Seems no one posted here yet and you have a great chance to attract maximum attention to your topic. So, what are you waiting for? <a href="http://sharexy.com" target="_blank">Go to Sharexy</a> and create your post Right Now!';
    var $rss = array(
    	0 => array('url' => 'http://sharexy.com/rss', 'title' => 'All Hubs'),
    	1 => array('url' => 'http://sharexy.com/rss/blog/Advertising/', 'title' => 'Advertising'),
        2 => array('url' => 'http://sharexy.com/rss/blog/art/', 'title' => 'Art'),
        3 => array('url' => 'http://sharexy.com/rss/blog/automotive/', 'title' => 'Automotive'),
        4 => array('url' => 'http://sharexy.com/rss/blog/beauty-and-style/', 'title' => 'Beauty & Style'),
        5 => array('url' => 'http://sharexy.com/rss/blog/blogging/', 'title' => 'Blogging'),
        6 => array('url' => 'http://sharexy.com/rss/blog/Books-and-Magazines/', 'title' => 'Books & Magazines'),
        7 => array('url' => 'http://sharexy.com/rss/blog/business/', 'title' => 'Business'),
        8 => array('url' => 'http://sharexy.com/rss/blog/celebrities/', 'title' => 'Celebrities'),
        9 => array('url' => 'http://sharexy.com/rss/blog/coding-and-development/', 'title' => 'Coding & Development'),
        10 => array('url' => 'http://sharexy.com/rss/blog/computers/', 'title' => 'Computers'),
        11 => array('url' => 'http://sharexy.com/rss/blog/cooking-food-recipes/', 'title' => 'Cooking, Food & Recipes'),
        12 => array('url' => 'http://sharexy.com/rss/blog/ecommerce/', 'title' => 'eCommerce'),
        13 => array('url' => 'http://sharexy.com/rss/blog/education/', 'title' => 'Education'),
        14 => array('url' => 'http://sharexy.com/rss/blog/employment-and-Jobs/', 'title' => 'Employment & Jobs'),
        15 => array('url' => 'http://sharexy.com/rss/blog/fashion-and-style/', 'title' => 'Fashion'),
        16 => array('url' => 'http://sharexy.com/rss/blog/finance/', 'title' => 'Finance'),
        17 => array('url' => 'http://sharexy.com/rss/blog/firearms/', 'title' => 'Firearms'),
        18 => array('url' => 'http://sharexy.com/rss/blog/freelance/', 'title' => 'Freelance'),
        19 => array('url' => 'http://sharexy.com/rss/blog/fun-and-humor/', 'title' => 'Fun & Humor'),
        20 => array('url' => 'http://sharexy.com/rss/blog/gadgets/', 'title' => 'Gadgets'),
        21 => array('url' => 'http://sharexy.com/rss/blog/games/', 'title' => 'Games'),
        22 => array('url' => 'http://sharexy.com/rss/blog/health-and-fitness/', 'title' => 'Health & Fitness'),
        23 => array('url' => 'http://sharexy.com/rss/blog/history/', 'title' => 'History'),
        24 => array('url' => 'http://sharexy.com/rss/blog/home-and-garden/', 'title' => 'Home and Garden'),
        25 => array('url' => 'http://sharexy.com/rss/blog/hosting-Servers-administration/', 'title' => 'Hosting, Servers & Administration'),
        26 => array('url' => 'http://sharexy.com/rss/blog/industrial-sector/', 'title' => 'Industrial Sector'),
        27 => array('url' => 'http://sharexy.com/rss/blog/insurance/', 'title' => 'Insurance'),
        28 => array('url' => 'http://sharexy.com/rss/blog/interior-design/', 'title' => 'Interior Design'),
        29 => array('url' => 'http://sharexy.com/rss/blog/kids-and-parenting/', 'title' => 'Kids & Parenting'),
        30 => array('url' => 'http://sharexy.com/rss/blog/law/', 'title' => 'Law'),
        31 => array('url' => 'http://sharexy.com/rss/blog/lifestyle/', 'title' => 'Lifestyle'),
        32 => array('url' => 'http://sharexy.com/rss/blog/literature-poems-poetry/', 'title' => 'Literature, Poems & Poetry'),
        33 => array('url' => 'http://sharexy.com/rss/blog/make-money/', 'title' => 'Make Money'),
        34 => array('url' => 'http://sharexy.com/rss/blog/marketing/', 'title' => 'Marketing'),
        35 => array('url' => 'http://sharexy.com/rss/blog/medicine/', 'title' => 'Medicine'),
        36 => array('url' => 'http://sharexy.com/rss/blog/mobile/', 'title' => 'Mobile'),
        37 => array('url' => 'http://sharexy.com/rss/blog/movies/', 'title' => 'Movies'),
        38 => array('url' => 'http://sharexy.com/rss/blog/music/', 'title' => 'Music'),
        39 => array('url' => 'http://sharexy.com/rss/blog/pets/', 'title' => 'Pets'),
        40 => array('url' => 'http://sharexy.com/rss/blog/photography/', 'title' => 'Photography'),
        41 => array('url' => 'http://sharexy.com/rss/blog/politics/', 'title' => 'Politics'),
        42 => array('url' => 'http://sharexy.com/rss/blog/quotes/', 'title' => 'Quotes'),
        43 => array('url' => 'http://sharexy.com/rss/blog/real-estate/', 'title' => 'Real Estate'),
        44 => array('url' => 'http://sharexy.com/rss/blog/religion/', 'title' => 'Religion'),
        45 => array('url' => 'http://sharexy.com/rss/blog/science/', 'title' => 'Science'),
        46 => array('url' => 'http://sharexy.com/rss/blog/sci-fi/', 'title' => 'Sci-fi'),
        47 => array('url' => 'http://sharexy.com/rss/blog/search-engines/', 'title' => 'Search Engines'),
        48 => array('url' => 'http://sharexy.com/rss/blog/security/', 'title' => 'Security'),
        49 => array('url' => 'http://sharexy.com/rss/blog/self-improvement/', 'title' => 'Self Improvement'),
        50 => array('url' => 'http://sharexy.com/rss/blog/shopping/', 'title' => 'Shopping'),
        51 => array('url' => 'http://sharexy.com/rss/blog/social-media/', 'title' => 'Social Media'),
        52 => array('url' => 'http://sharexy.com/rss/blog/sport/', 'title' => 'Sport'),
        53 => array('url' => 'http://sharexy.com/rss/blog/startups/', 'title' => 'Startups'),
        54 => array('url' => 'http://sharexy.com/rss/blog/technology/', 'title' => 'Technology'),
        55 => array('url' => 'http://sharexy.com/rss/blog/television/', 'title' => 'Television'),
        56 => array('url' => 'http://sharexy.com/rss/blog/travel/', 'title' => 'Travel'),
        57 => array('url' => 'http://sharexy.com/rss/blog/web-analytics/', 'title' => 'Web Analytics'),
        58 => array('url' => 'http://sharexy.com/rss/blog/web-design/', 'title' => 'Web Design'),
		59 => array('url' => 'http://sharexy.com/rss/blog/weddings/', 'title' => 'Weddings'),
		60 => array('url' => 'http://sharexy.com/rss/blog/writing/', 'title' => 'Writing'),
		61 => array('url' => 'http://sharexy.com/rss/blog/diy/', 'title' => 'DIY'),
		62 => array('url' => 'http://sharexy.com/rss/blog/love-and-relationships/', 'title' => 'Love & Relationships'),
    );

    function SharexyAdmin() {
        $this->parentInit();
        $this->adminMenu = array(
			array(
				"parent_slug" => "sharexy-menu" ,
				"page_title" => "Sharing Tool",
				"menu_title" => "Sharing Tool",
				"capability" => "manage_options",
				"menu_slug" => "sharexy-menu",
				"function" => array(&$this, 'buttonsSettings'),
				"icon_url" => $this->params['logo']['small_img'] ? $this->params['logo']['path'] . $this->params['logo']['small_img'] : '',
				"position" => NULL
			),
			array(
				"parent_slug" => "sharexy-menu" ,
				"page_title" => "Network Buzz",
				"menu_title" => "Network Buzz",
				"capability" => "manage_options",
				"menu_slug" => "sharexy-menu-community",
				"function" => array(&$this, 'buttonsCommunity'),
				"icon_url" => $this->params['logo']['small_img'] ? $this->params['logo']['path'] . $this->params['logo']['small_img'] : '',
				"position" => NULL
			),
        );
    }

    function setErrorObject($errorReporter) {
        $this->errorReporter = $errorReporter;
    }
    
    function initAjax() {
		if (!empty($_POST['ajax'])) {
	    	if (isset($_POST['rss'])) {
	    		$rss = $_POST['rss'];
	    		update_option('sharexy_news_rss', $rss);
	    		update_option('sharexy_news_new_'.$rss, 0);
	    		$news = $this->getNews();
	    		$textNoNews = $this->textNoNews;
		    	
	    		ob_start();
		    	include "templates/ajax-news.phtml";
		    	$out = ob_get_contents();
		    	ob_end_clean();
		    	echo $out;
		    	exit;
			}
		   	if (isset($_POST['rsswidget'])) {
	    		$rss = $_POST['rsswidget'];
	    		update_option('sharexy_news_rss', $rss);
		   		
	    		$lastts = get_option('sharexy_news_last_update_'.get_option('sharexy_news_rss'));
				if (empty($lastts) || ($lastts + 3600 * 24) < time()) {
					$news = $this->getNews();
				} else {
					$news = get_option('sharexy_news_'.get_option('sharexy_news_rss'));
				}
	    		$textNoNews = $this->textNoNews;
		    	
	    		ob_start();
		    	include "templates/ajax-newswidget.phtml";
		    	$out = ob_get_contents();
		    	ob_end_clean();
		    	echo $out;
		    	exit;
			}
		}
    }
    
    function initNews() {
		$rssId = get_option('sharexy_news_rss');
    	if (empty($rssId) || !in_array($rssId, array_keys($this->rss))) {
    		$rssId = 0;
    		update_option('sharexy_news_rss', $rssId);
    	}
    	$lastts = get_option('sharexy_news_last_update_'.$rssId);
		if (empty($lastts) || ($lastts + 3600 * 24) < time()) {
			$news = $this->getNews();
		}
    }
    
    function composeMenuBar() {
        if (!is_array($this->adminMenu) || !(count($this->adminMenu) > 0)) {
            return;
        }
        
        $menuTitle = 'Sharexy';
        $cnt = get_option('sharexy_news_new_'.get_option('sharexy_news_rss'));
        $cnt = intval($cnt);
        if ($cnt > 0) $menuTitle .= ' <span class="update-plugins count-1"><span class="update-count">'.$cnt.'</span></span>';
        add_menu_page('Sharexy', $menuTitle, 'manage_options', 'sharexy-menu', array(&$this, 'buttonsSettings'), plugin_dir_url(__FILE__).'img/favicon.png');
        
        $menuPagesCount = count($this->adminMenu);
        for ($i = 0; $i < $menuPagesCount; $i++) {
        	$subMenu = $this->adminMenu[$i];
			$parent_slug = isset($subMenu['parent_slug']) ? $subMenu['parent_slug'] : '';
			$page_title = isset($subMenu['page_title']) ? $subMenu['page_title'] : '';
			$menu_title = isset($subMenu['menu_title']) ? $subMenu['menu_title'] : '';
			$capability = isset($subMenu['capability']) ? $subMenu['capability'] : '';
			$menu_slug = isset($subMenu['menu_slug']) ? $subMenu['menu_slug'] : '';
			$function = isset($subMenu['function']) ? $subMenu['function'] : '';
        	if ($menu_slug == 'sharexy-menu-community' && $cnt > 0) {
				$menu_title .= ' <span class="update-plugins count-1"><span class="update-count">'.$cnt.'</span></span>';
			}
			add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
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
    
    function buttonsCommunity() {
    	if (!is_user_logged_in() || !is_admin() ) {
    		wp_die('hacking??');
    		return;
    	}
   		$this->constructorCommunityTpl();
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
        $scriptPath = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] .'/'.$this->params['server']['scriptPath'];
        include "templates/constructor.phtml";
    }
    
    function constructorCommunityTpl() {
    	$logoSRC = $this->params['logo']['big_img'] ? $this->params['logo']['path'] . $this->params['logo']['big_img'] : '';
    	$imgPath = $this->params['server']['imgPath'];
    	$scriptPath = $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] .'/'.$this->params['server']['scriptPath'];
    	
    	$rssarr = $this->rss;
    	$rssId = get_option('sharexy_news_rss');
    	
    	include "templates/community.phtml";
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

    function getDesignNames() {
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
                'url' => $init->url
            );
            $iteration++;
        }
        return $designs;
    }

    /* Notices */
    
    function showNotices() {
    	if (!get_option('sharexy_notice1_hide')) {
    		$uri = $_SERVER['REQUEST_URI'];
    		if (strpos($uri, '?') === false) {
    			$uri .= '?sharexy_notice1_hide=1';
    		} else {
    			$uri .= '&sharexy_notice1_hide=1';
    		}
	    	echo '<div class="updated">
	    	<p>
	    		<span style="float: right; margin-left: 40px;"><a href="'.$uri.'">hide</a></span>
	    		<b>Woohoo!</b> We have created a new cosy plase for bloggers just Like You! Want to make yourself and your blog more popular and have something interesting to share? <a href="http://sharexy.com/?utm_source=plugin_notice" target="_blank"><b>Join the Sharexy today, we have cookies!</b></a><br />
	    		Also we have added new tool - <a href="admin.php?page=sharexy-menu-community">Network Buzz Monitoring</a>, which allows you to see what other bloggers are posting in the categories of your interest and to be updated with the latest news and trends.
	    	</p>
	    	</div>';
    	}
    }
    
    function hideNotices() {
    	if (isset($_GET['sharexy_notice1_hide']) && $_GET['sharexy_notice1_hide'] == 1) {
    		update_option('sharexy_notice1_hide', 1);
    	}
    }
    
    function initNotices() {
    	add_action('admin_notices', array(&$this, 'showNotices'));
    	add_action('admin_init', array(&$this, 'hideNotices'));
    }
    
    /* RSS News */
    
    function getNewsFromCache() {
    	return get_option('sharexy_news_'.get_option('sharexy_news_rss'), array());
    }
    
    function getNewsFromRss() {
    	$rssId = get_option('sharexy_news_rss');
    	$rss = $this->rss[$rssId]['url'];
    	update_option('sharexy_news_last_update_'.$rssId, time());
    	if (function_exists('curl_init') && function_exists('simplexml_load_string')) {
    		$ch = curl_init($rss);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		$data = curl_exec($ch);
    		curl_close($ch);
    		$data = simplexml_load_string($data, null, LIBXML_NOCDATA);
    		$news = array();
    		foreach ($data->channel->item as $item) {
    			$item = get_object_vars($item);
    			$bl = bitly_v3_shorten($item['link']);
    			$item['link'] = $bl['url'];
    			$news[] = $item;
    		}
    		return $news;
    	} elseif (function_exists('file_get_contents') && function_exists('simplexml_load_string')) {
    		$data = file_get_contents($rss);
    		$data = simplexml_load_string($data, null, LIBXML_NOCDATA);
    		$news = array();
    		foreach ($data->channel->item as $item) {
    			$item = get_object_vars($item);
    			$bl = bitly_v3_shorten($item['link']);
    			$item['link'] = $bl['url'];
    			$news[] = $item;
    		}
    		return $news;
    	} else {
    		return 'You need SimpleXML and cURL or file_get_contents enabled on the server.';
    	}
    }
    
	function getNews() {
		$newsRss = $this->getNewsFromRss();
		if (is_array($newsRss)) {
			$newsCache = $this->getNewsFromCache();
			$cntNew = 0;
			if (!empty($newsCache)) {
				$lastts = strtotime($newsCache[0]['pubDate']);
				$news = array();
				foreach ($newsRss as $item) {
					if (strtotime($item['pubDate']) > $lastts) {
						$news[] = $item;
						$cntNew++;
					}
				}
				foreach ($newsCache as $item) $news[] = $item;
			} else {
				$news = $newsRss;
				$cntNew = count($newsRss);
			}
			$news = array_slice($news, 0, 10);
			if ($cntNew > 10) $cntNew = 10;
			update_option('sharexy_news_'.get_option('sharexy_news_rss'), $news);
			update_option('sharexy_news_new_'.get_option('sharexy_news_rss'), $cntNew);
			return $news;
		} else {
			return $newsRss;
		}
	}
	
	function showDashboardWidget() {
		$rssId = get_option('sharexy_news_rss');
		echo '<div class="rss-widget">';
		echo "
			<script type='text/javascript'>
			function loadNews() {
				jQuery('#NewsWidget').hide();
				jQuery('#HubLoader').show();
				var rss = jQuery('#SelectHub').val();
				jQuery.post('".$_SERVER['REQUEST_URI']."', {'rsswidget': rss, 'ajax': 1},
					function(data) {
						jQuery('#NewsWidget').html(data);
						jQuery('#HubLoader').hide();
						jQuery('#NewsWidget').show();
					},
					'html');
			}
			jQuery(document).ready(function() { loadNews(); });
			</script>
		";
		echo '<p style="float: right; margin-left: 10px; margin-top: 0; font-size: 10px;">';
		echo 'Choose a Hub: ';
		echo '<select id="SelectHub" onchange="loadNews();" style="width: 120px;">';
		foreach ($this->rss as $id => $val) {
			echo '<option value="'.$id.'" '.($id == $rssId ? 'selected="selected"' : '').'>'.$val['title'].'</option>';
		}
		echo '</select>';
		echo '</p>';
		echo '<p>
			<b>Have something interesting to share? Put your link here.</b><br />
			<b><a href="http://sharexy.com/?utm_source=dashboard_plugin_link" target="_blank" style="color: #C7531F; text-decoration: underline;">Join Sharexy Today and Create Your Post!</a></b></p>';
		echo '<div id="HubLoader" style="text-align: center;"><img src="'.$this->params['server']['imgPath'].'loading.gif" /></div>';
		echo '<div id="NewsWidget"></div>';
		echo '</div>';
	}
	
	function addDashboardWidget() {
		wp_add_dashboard_widget('dashboard_widget', 'Sharexy Network Buzz', array(&$this, 'showDashboardWidget'));
	}
	
	function initDashboardWidget() {
		add_action('wp_dashboard_setup', array(&$this, 'addDashboardWidget'));
	}
    
}