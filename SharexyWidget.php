<?php

class SharexyWidget extends SharexyMain {
    var $errorReporter = null,
        $codeType = array("lite" => false, "full" => false),
        $noindexClassName = 'sharexyWidgetNoindexUniqueClassName',
        $priority = 16,
        $defaultPriority = 10,
        $placeIds, $dataScripts;

    function SharexyWidget() {
        $this->parentInit();
        $this->dataScripts = array();
        $this->placeIds = array();
    }

    function setErrorObject($errorReporter) {
        $this->errorReporter = $errorReporter;
    }    

    function loadWidget() {
        $stlPrms = $this->getStyle();        
        $currUrl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];        
        $currUrl = mb_strtoupper(trim($currUrl));
        $urls = isset($stlPrms['hide_on_urls'])?trim($stlPrms['hide_on_urls']):'';
        if (strlen($urls) > 0) {
            $urls = explode ( ',' , $urls);
            foreach ($urls as $url) {
                $mask =  mb_strtoupper(trim($url));
                $mask =  str_replace("HTTP://" , "", $mask);
                $mask =  str_replace("HTTPS://" , "", $mask);
                $mask =  str_replace("*" , ".*", $mask);
                $mask =  str_replace("/" , "\/", $mask);
                $mask = "/^".$mask."$/"; 
                /*echo "<br>";    
                echo "<br>".$currUrl;
                echo "<br>".$mask;*/
                if (preg_match($mask, $currUrl) > 0) {
                    return;
                }
            }
        }
        
        
        add_filter('get_the_excerpt', array(&$this, 'displayWidgetExcerpt'), $this->priority);
        add_filter('the_content', array(&$this, 'displayWidget'), $this->priority);
        
        add_shortcode('sharexy', array(&$this, 'displayPHPShortcode'));
        add_filter('the_content', array(&$this, 'displayShortcode'), $this->priority);
        
        add_action('wp_footer', array(&$this, 'displayFloatWidget'), $this->priority);

        add_action('wp_footer', array(&$this, 'dataScriptsLoader'), $this->priority + 1);
        add_action('wp_footer', array(&$this, 'footLoader'), $this->priority + 2);
    }

    function displayWidgetExcerpt($content = '') {
        return has_excerpt() ? $this->displayWidget($content) : $content;
    }

    function removeTag($content, $text = '') {
        if ($text !== '') {
            return $content;
        }
        $text = get_the_content('');
        $text = strip_shortcodes( $text );
        remove_filter('the_content', array(&$this, 'displayWidget'), $this->defaultPriority - 1);
        $text = apply_filters('the_content', $text);
        add_filter('the_content', array(&$this, 'displayWidget'), $this->defaultPriority + 1);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text);
        $excerpt_length = apply_filters('excerpt_length', 55);
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
        return $this->displayWidget($text, false);
    }

    function displayWidget($content = '', $filtered = true) {
        $pageHTML = '';
        remove_filter('wp_trim_excerpt', array(&$this, 'removeTag'), $this->defaultPriority - 1, 2);
        $placements = $this->getPlacements();
        $mainStyle = $this->getStyle();
        $pageHTML .= $this->getPlaceCode('top', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('top_post', $placements, $mainStyle);
        $pageHTML .= $content;
        $pageHTML .= $this->getPlaceCode('bottom', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('bottom_post', $placements, $mainStyle);
        if ($filtered === true) {
            add_filter('wp_trim_excerpt', array(&$this, 'removeTag'), $this->priority, 2);
        }
        return $pageHTML;
    }

    function displayShortcode($content) {
    	$offset = 0;
    	$out = '';
    	if (function_exists('mb_strpos')) {            
	    	while (($ps = mb_strpos($content, '[sharexy]', $offset, 'utf-8')) !== false) {
	    		$out .= mb_substr($content, $offset, $ps - $offset, 'utf-8');
		    	$placements = $this->getPlacements();
		    	$mainStyle = $this->getStyle();
		    	$widget = $this->getPlaceCode('shortcode', $placements, $mainStyle);
		    	$out .= $widget;
		    	$offset = $ps + 9;
	    	}
	    	$out .= mb_substr($content, $offset, mb_strlen($content, 'utf-8'), 'utf-8');
    	} else {
    		while (($ps = strpos($content, '[sharexy]', $offset)) !== false) {
    			$out .= substr($content, $offset, $ps - $offset);
    			$placements = $this->getPlacements();
    			$mainStyle = $this->getStyle();
    			$widget = $this->getPlaceCode('shortcode', $placements, $mainStyle);
    			$out .= $widget;
    			$offset = $ps + 9;
    		}
    		$out .= substr($content, $offset, strlen($content));
    	}
    	return $out;
    }

    function displayPHPShortcode() {
            $placements = $this->getPlacements();
            $mainStyle = $this->getStyle();
            $widget = $this->getPlaceCode('shortcode', $placements, $mainStyle);
            return $widget;
    }
    
    function displayFloatWidget() {
        $placements = $this->getPlacements();
        $mainStyle = $this->getStyle();
        echo $this->getPlaceCode('float', $placements, $mainStyle);
    }

    function getPlaceCode($place, &$placements, &$mainStyle) {
        $pageHTML = '';
        if (!isset($placements[$place]) || !is_array($placements[$place]) || !isset($placements[$place]['display']) || $placements[$place]['display'] !== 1) {
            return $pageHTML;
        }
        $customLink = false;
        $customTitle = false;
        $placeParams = $placements[$place];
        $show = false;
        if (
            is_home() && !is_paged()
            && isset($placeParams['pages_mode']['front'])
            && $placeParams['pages_mode']['front'] === 1
        ) {
            $customLink = get_permalink();
            $customTitle = get_the_title();
            $show = true;
        } elseif (
            (!is_home() || is_paged()) && !is_single() && !is_page()
            && isset($placeParams['pages_mode']['page'])
            && $placeParams['pages_mode']['page'] === 1
        ) {
            $customLink = get_permalink();
            $customTitle = get_the_title();
            $show = true;
        } elseif (
            (is_single() || is_page())
            && isset($placeParams['pages_mode']['post'])
            && $placeParams['pages_mode']['post'] === 1
        ) {
            $show = true;
        }
        if (!$show) {
            return $pageHTML;
        }

        $placeStyle = $this->getPlacementsStyleParams($place);
        $mixStyle = $this->mixMainPlaceStyleParams($mainStyle, $placeStyle);
        if ($customLink && $customTitle && $place !== 'float') {
            $mixStyle['customLink'] = $customLink;
            $mixStyle['customTitle'] = $customTitle;
        }
        return $this->getSharexyCodeHTML($place, $mixStyle, $placeParams);
    }

    function getSharexyCodeHTML($place, $styleParams, $placeParams) {
        global $post;
        $key = isset($post) && $post && isset($post->ID) ? md5($place . $post->ID) : md5($place);
        $debugInfo = isset($post) && $post && isset($post->ID) ? $place . " " . $post->ID : $place;
        if ($place == 'shortcode') {
        	$this->placeIds[$key] = rand(999999, 99999999);
        } else {
        	$this->placeIds[$key] = isset($this->placeIds[$key]) ? $this->placeIds[$key] : rand(999999, 99999999);
        }
        $code_id = $this->placeIds[$key];

        $this->codeType['lite'] = isset($styleParams['user_id']) && $this->validateWebmasterId($styleParams['user_id'])? false : true;
        $this->codeType['full'] = isset($styleParams['user_id']) && $this->validateWebmasterId($styleParams['user_id']) ? true : false;

        $styleParams['publisher_key'] = isset($styleParams['user_id']) ? $this->validateWebmasterId($styleParams['user_id']) : 0;
        $styleParams['code_id'] = $code_id;
        $styleParams['d'] = $debugInfo;
        $styleParams['popup_bot_a'] = 1;
        $align        = isset($placeParams['align']) ? $placeParams['align'] : "";
        $styleParams['counters_align']  = isset($placeParams['counters_align']) ? $placeParams['counters_align'] : "none";
        
        $this->dataScripts[$code_id] = $styleParams;

        return '<div align="' . $align . '"><div class="' . $this->noindexClassName . '"><div id="shr_' . $code_id . '"></div></div></div>';
    }

    function dataScriptsLoader() {
        if (is_array($this->dataScripts) && !empty($this->dataScripts)) {
            $script = <<<EOF
                <script type='text/javascript'>/* <![CDATA[ */
                    (function(w){
                        if (!w.SharexyWidget) {
                            w.SharexyWidget = {
                                Params : {}
                            };
                        }
EOF;
            foreach ($this->dataScripts as $id => $styleParams) {
                $styleParams['localImg']       = plugin_dir_url(__FILE__).'design';
            	$styleParams['mailScript']     = plugin_dir_url(__FILE__).'sharexymail.php';
                $styleParams['ajaxResponder']  = plugin_dir_url(__FILE__).'ajaxresponder.php';
                $styleParams['local_counters'] = '1';
                unset($styleParams['bitly_access']);
                unset($styleParams['bitly_not']);
                if (function_exists('json_encode')) {
                    $params = json_encode($styleParams);
                } else {
                    $json = new SharexyJson();
                    $params = $json->encode($styleParams);
                }

                $script .= "w.SharexyWidget.Params['shr_{$id}'] = {$params};\n";
            }
            $script .= "})(window);
                /* ]]> */
            </script>
            ";
            echo $script;
        }
    }

    function footLoader() {
        if ($this->codeType['full'] === true || $this->codeType['lite'] === true) {
            echo <<<EOF
                <script type='text/javascript'>/* <![CDATA[ */
                    (function (w) {
                        if (!w.jQuery) {
                            return;
                        }
                        var jQuery = w.jQuery;
                        jQuery('.{$this->noindexClassName}').each(function (n, element) {
                            var content = jQuery(element).html();
                            jQuery(element).html(content);
                        });
                    })(window);
                 /* ]]> */</script>
EOF;
        }
        if ($this->codeType['full'] === true) {
            echo '<script type="text/javascript" src="' . $this->params['server']['protocol'] . '//' . $this->params['server']['host'] . $this->params['server']['port'] . '/' . $this->params['server']['scriptLoader'] . '"></script>';
        } elseif ($this->codeType['lite'] === true) {
            echo '<script type="text/javascript" src="' . $this->params['server']['protocol'] . '//' . $this->params['server']['host'] . $this->params['server']['port'] . '/' . $this->params['server']['scriptLoaderLite'] . '"></script>';
        }
    }

}

