<?php

/*
 * This file is part of the Link Checker package.
 *
 * (c) ZHAW HSB <apps.hsb@zhaw.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Application;

require_once '../configuration.php';
require VENDOR_DIR . '/autoload.php';

/**
 * No router class provided, render initial view...
 */
(new Application())->indexAction();
