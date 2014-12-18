<?php
/**
 * Created by Aashwin Mohan
 * Copyright 2014
 * File: application.config.php
 * Date: 17/11/14
 * Time: 18:04
 */

date_default_timezone_set('Europe/London');
define('APP_DIR', __DIR__.'/application');
define('SYS_DIR', __DIR__.'/system');
define('WEB_DOMAIN', 'www.explodingweb.com');
define('WWW_ROOT', 'http://'.WEB_DOMAIN.'/WAD');
define('WWW_PUBLIC', WWW_ROOT.'/application/public');
define('404_PAGE', WWW_ROOT.'/404/');
define('HASH_COST', 13);
define('COOKIE_SALT', 'da39a3ee5e6b4b0d3255bfef95601890afd80709');
define('DEBUG_MODE',true);

define('DB_DATE_FORMAT','Y-m-d');
define('DB_DATETIME_FORMAT','Y-m-d H:i:s');
define('DISPLAY_DATETIME','Y-m-d H:i');

define('TEAM_FLAG_DIR', APP_DIR.'/public/uploads/team_flags/');
define('WWW_TEAM_FLAG', WWW_PUBLIC.'/uploads/team_flags/');
?>