<?php

/*
 * This file is part of the Link Checker package.
 *
 * (c) ZHAW HSB <apps.hsb@zhaw.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Global configuration constants
 */

// API Keys
define('ALMA_API_KEY', '');

// Names
define('ORGANIZATION_NAME', 'The Library');
define('APP_NAME', 'Alma Link Checker');

// Cache
define('CACHE_DIR', dirname(__FILE__) . '/cache');
define('CACHE_DURATION', 36000);

// Directories
define('VENDOR_DIR', dirname(__FILE__) . '/vendor');
define('VIEW_DIR', dirname(__FILE__) . '/resources/views');
define('PARTIAL_DIR', dirname(__FILE__) . '/resources/partials');
define('LANG_DIR', dirname(__FILE__) . '/resources/translations');

// HTTP
$scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
$schemeAndHost = $scheme . '://' . $_SERVER['HTTP_HOST'];

define('BASE_PATH', $schemeAndHost);
define('CSS_DIR_HTTP', $schemeAndHost . '/css');
define('JS_DIR_HTTP', $schemeAndHost . '/js');
define('ASSET_DIR_HTTP', $schemeAndHost . '/assets');

/**
 * Languages
 */
$GLOBALS['languages']['fallback'] = 'de';
$GLOBALS['languages']['de'] = require_once LANG_DIR . '/de.php';
$GLOBALS['languages']['en'] = require_once LANG_DIR . '/en.php';
$GLOBALS['languages']['fr'] = require_once LANG_DIR . '/fr.php';
$GLOBALS['languages']['it'] = require_once LANG_DIR . '/it.php';
