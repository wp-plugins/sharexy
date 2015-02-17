<?php
/*
    Plugin Name: Sharexy
    Plugin URI: http://wordpress.org/extend/plugins/sharexy/
    Description: Sharexy social buttons.
    Author: Sharexy.com
    Version: 4.2.2
    Author URI: http://sharexy.com/
    License: GPLv2 or later
*/
/*
    Copyright 2013  sharexy.com  (email: support@sharexy.com)

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

/*error_reporting( E_ALL );
ini_set( "display_errors", 1 );*/

if (!defined('SHAREXY_WIDGET_INIT')) {
    define('SHAREXY_WIDGET_INIT', true);
} else {
    return;
}

if (class_exists("SharexyErrorReporter") || class_exists("SharexyMain") || class_exists("SharexyWidget") || class_exists("SharexyAdmnin") || class_exists("SharexyView")) {
    $sharexyErrorCallbackFunction = create_function('$content = ""', '
        $content .= "
            <div style=\"background: none repeat scroll 0 0 #FFFFE4;border: 1px solid #FFBC9F;color: #646974;font-size: 12px;line-height: 20px;margin-bottom: 20px;padding: 3px 7px;text-align: center;\">
                    Sharexy plugin conflict namespace. Class SharexyErrorReporter, SharexyWidget, SharexyAdmnin, SharexyMain is all ready exists.
            </div>
        ";
        return $content;
    ');
    add_filter('the_content', $sharexyErrorCallbackFunction, 20);
    add_filter('get_the_excerpt', $sharexyErrorCallbackFunction, 20);
} else {
    require_once "SharexyError.php";
    require_once "SharexyMain.php";
    require_once "SharexyWidget.php";
    require_once "SharexyAdmin.php";
    require_once "SharexyView.php";
    require_once "SharexyJson.php";
    $sharexy;
    $sharexy = new SharexyView(new SharexyWidget(), new SharexyAdmin(), new SharexyErrorReporter());
    $sharexy->initWidget();
    $sharexy->initAdmin();     
}

