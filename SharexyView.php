<?php

class SharexyView extends SharexyMain {
    private $widget, $admin, $errorReporter;
    public function __construct(SharexyWidget &$widget, SharexyAdmin &$admin, SharexyErrorReporter &$errorReporter) {
        $this->widget = $widget;
        $this->admin = $admin;
        $this->errorReporter = $errorReporter;
        $this->admin->setErrorObject($this->errorReporter);
        $this->widget->setErrorObject($this->errorReporter);
        parent::__construct();
    }

    public function initWidget() {
        $this->widget->loadWidget();
    }

    public function initAdmin() {
        $this->admin->initMenu();
        $this->admin->registerAdminScripts();
        $this->admin->registerAdminCSS();
    }
}
