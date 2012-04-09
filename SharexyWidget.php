<?php
class SharexyWidget extends SharexyMain {

    private $errorReporter = null;

    public function __construct() {
        parent::__construct();
    }

    public function setErrorObject(SharexyErrorReporter $errorReporter) {
        $this->errorReporter = $errorReporter;
    }

    public function loadWidget() {
        add_action('the_content', array(&$this, 'displayWidget'), 10);
    }

    public function displayWidget($content = '') {
        $pageHTML = '';
        $placements = $this->getPlacements();
        $mainStyle = $this->getStyle();
        $pageHTML .= $this->getPlaceCode('top', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('top_post', $placements, $mainStyle);
        $pageHTML .= $content;
        $pageHTML .= $this->getPlaceCode('bottom', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('bottom_post', $placements, $mainStyle);
        $pageHTML .= $this->getPlaceCode('float', $placements, $mainStyle);
        echo $pageHTML;
    }

    private function getPlaceCode($place, &$placements, &$mainStyle) {
        $pageHTML = '';
        if (!isset($placements[$place]) || !is_array($placements[$place]) || !isset($placements[$place]['display']) || $placements[$place]['display'] !== 1) {
            return $pageHTML;
        }

        $customLink = false;
        $customTitle = false;

        $placeParams = $placements[$place];
        $show = false;
        if (
            is_home() && !is_paged() //только для главной
            && isset($placeParams['pages_mode']['front'])
            && $placeParams['pages_mode']['front'] === 1
        ) {
            $customLink = get_permalink();
            $customTitle = get_the_title();
            $show = true;
        } elseif (
            (!is_home() || is_paged()) && !is_single() && !is_page() //не для главной и не для постов
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
        if ($customLink && $customTitle) {
            $mixStyle['customLink'] = $customLink;
            $mixStyle['customTitle'] = $customTitle;
        }
        $code = $this->getSharexyCodeHTML($mixStyle, $placeParams);
        $pageHTML .= $code;
        return $pageHTML;
    }

    private function getSharexyCodeHTML($styleParams, $placeParams) {
        $code_id = rand(999999, 99999999);
        $code = "";
        $code .= "
            <noindex>
            <div align='";
        $code .= isset($placeParams['align']) ? $placeParams['align'] : "";
        $code .= "'>
                <div id='shr_" . $code_id . "'>
                    <script type='text/javascript'>
                        (function(w){
                            if (!w.SharexyWidget) { w.SharexyWidget = {};} if (!w.SharexyWidget.Params) { w.SharexyWidget.Params = {}; } w.SharexyWidget.Params['shr_{$code_id}'] = {";
        $styleParams['publisher_key'] = isset($styleParams['user_id']) ? $styleParams['user_id']:'0';
        foreach ($styleParams as $key => $value) {
            $code .= " '" . $key . "' : ";
            if (is_array($value)) {
                $code .= count($value) > 0 ? "['" . implode("', '", $value) . "']" : "[]";
            } else {
                $code .= "'" . str_replace("'", "\\'", $value) . "'";
            }
            $code .= ", ";
        }
        $code .= " 'code_id' : '" . $code_id . "'
                            };
                        })(window)
                    </script><script type='text/javascript' src='" . $this->params['server']['protocol'] . "//" . $this->params['server']['host'] . $this->params['server']['port'] . "/" ;
        $code .= $styleParams['publisher_key'] ? $this->params['server']['scriptLoader'] : $this->params['server']['scriptLoaderLite'];
        $code .= "'></script>
                </div>
            </div>
            </noindex>
        ";
        return $code;
    }

}

