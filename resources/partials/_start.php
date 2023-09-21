<?php

/*
 * This file is part of the Link Checker package.
 *
 * (c) ZHAW HSB <apps.hsb@zhaw.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\Service\LanguageService;

?>
<!DOCTYPE html>
<html lang="<?php echo LanguageService::isFallbackLanguage() ? '' : LanguageService::$language; ?>">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo CSS_DIR_HTTP; ?>/bootstrap.css">
    <link rel="stylesheet" href="<?php echo CSS_DIR_HTTP; ?>/style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>

<body>
    <div id="loader">
        <div class="dual-ring-loader"></div>
    </div>
    <div id="alert-message-container" class="alert d-none"></div>