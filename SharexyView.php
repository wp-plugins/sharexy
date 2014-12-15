<?php
class SharexyView extends SharexyMain {
    var $widget, $admin, $errorReporter;
    function SharexyView(&$widget, &$admin, &$errorReporter) {
        $this->widget = $widget;
        $this->admin = $admin;
        $this->errorReporter = $errorReporter;
        $this->admin->setErrorObject($this->errorReporter);
        $this->widget->setErrorObject($this->errorReporter);
        $this->parentInit();
    }

    function initWidget() {
        $this->widget->loadWidget();
    }

    function initAdmin() {
        $this->admin->initMenu();
        $this->admin->registerAdminScripts();
        $this->admin->registerAdminCSS();
        $this->admin->initMessage();
    }
}
