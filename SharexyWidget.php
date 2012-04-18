<?php

class SharexyWidget extends SharexyMain {
    var $errorReporter = null,
        $codeType = array("lite" => false, "full" => true),
        $noindexClassName = 'sharexyWidgetNoindexUniqueClassName',
        $priority = 16,
        $defaultPriority = 10;

    function SharexyWidget() {
        $this->parentInit();
    }

    function setErrorObject($errorReporter) {
        $this->errorReporter = $errorReporter;
    }

    function loadWidget() {
        add_filter('get_the_excerpt', array(&$this, 'displayWidgetExcerpt'), $this->priority);
        add_filter('the_content', array(&$this, 'displayWidget'), $this->priority);
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
        remove_action('wp_footer', array(&$this, 'footLoader'), $this->defaultPriority - 1);
        $placements = $this->getPlacements();
        $mainStyle = $this->getStyle();
        $pageHTML .= $this->getPlaceCode('top', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('top_post', $placements, $mainStyle);
        $pageHTML .= $content;
        $pageHTML .= $this->getPlaceCode('bottom', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('bottom_post', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('float', $placements, $mainStyle);
        if ($filtered === true) {
            add_filter('wp_trim_excerpt', array(&$this, 'removeTag'), $this->priority, 2);
        }
        add_action('wp_footer', array(&$this, 'footLoader'), $this->priority + 1);
        return $pageHTML;
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
        return $this->getSharexyCodeHTML($mixStyle, $placeParams);
    }

    function getSharexyCodeHTML($styleParams, $placeParams) {
        $code_id = rand(999999, 99999999);
        $this->codeType['lite'] = isset($styleParams['user_id']) && $this->validateWebmasterId($styleParams['user_id'])? false : true;
        $this->codeType['full'] = isset($styleParams['user_id']) && $this->validateWebmasterId($styleParams['user_id']) ? true : false;

        $styleParams['publisher_key'] = isset($styleParams['user_id']) ? $this->validateWebmasterId($styleParams['user_id']) : 0;
        $styleParams['code_id'] = $code_id;
        $align = isset($placeParams['align']) ? $placeParams['align'] : "";
        if (function_exists('json_encode')) {
            $params = json_encode($styleParams);
        } else {
            $json = new SharexyJson();
            $params = $json->encode($styleParams);
        }
        $script = "<script type='text/javascript'><!--
                        (function(w){
                            if (!w.SharexyWidget) {w.SharexyWidget = {Params : {}};}
                            w.SharexyWidget.Params['shr_{$code_id}'] = " . $params . ";";
        $script .= "})(window);
                        //-->
                    </script>";
        return '<div align="' . $align . '"><div class="' . $this->noindexClassName . '"><div id="shr_' . $code_id . '"></div></div><div>' . $script . '</div></div>';
    }

    function footLoader() {
        if ($this->codeType['full'] === true || $this->codeType['lite'] === true) {
            echo <<<EOF
                <script type='text/javascript'><!--
                    (function (w) {
                        if (!w.jQuery) {
                            return;
                        }
                        var jQuery = window.jQuery;
                        jQuery('.{$this->noindexClassName}').each(function (n, element) {
                            var content = jQuery(element).html();
                            jQuery(element).html('<noindex>' + content + '</noindex>');
                        });
                    })(window);
                    //-->
                </script>
EOF;
        }
        if ($this->codeType['full'] === true) {
            echo '<script type="text/javascript" src="' . $this->params['server']['protocol'] . '//' . $this->params['server']['host'] . $this->params['server']['port'] . '/' . $this->params['server']['scriptLoader'] . '"></script>';
        } elseif ($this->codeType['lite'] === true) {
            echo '<script type="text/javascript" src="' . $this->params['server']['protocol'] . '//' . $this->params['server']['host'] . $this->params['server']['port'] . '/' . $this->params['server']['scriptLoaderLite'] . '"></script>';
        }
    }

}

