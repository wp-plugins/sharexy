<?php

class SharexyErrorReporter {
    var $supportEmail = 'support@sharexy.com';
    var $errorMessage;
    function SharexyErrorReporter($err = '') {
        $this->errorMessage = trim($err);
    }
    function setErrorMessage($err = '') {
        $this->errorMessage .= $this->errorMessage ? '<hr>' : '';
        $this->errorMessage .= trim($err);
    }
    function setErrorContent($content = '') {
        $errHtml = '';
        if ($this->errorMessage) {
            $errHtml = "
                <div style='
                    background: none repeat scroll 0 0 #FFFFE4;
                    border: 1px solid #FFBC9F;
                    color: #646974;
                    font-size: 12px;
                    line-height: 20px;
                    margin-bottom: 20px;
                    padding: 3px 7px;
                    text-align: center;'
                > " . $this->errorMessage . "
                </div>";
        }
        echo $content . $errHtml;
    }
    function runError() {
        add_filter('the_content', array(&$this, 'setErrorContent'), 10);
    }
}