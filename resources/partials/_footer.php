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

<footer>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 text-start">
                <div>
                    &copy; <?php echo ORGANIZATION_NAME; ?>
                </div>
            </div>
            <div class="col-12 col-lg-6 text-end">
                <ul class="list-inline mb-0">
                    <?php foreach ($GLOBALS['languages'] as $lang => $labels) : ?>
                        <?php if ($lang === 'fallback') : ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php $langCode = strtoupper($lang); ?>
                        <li class="list-inline-item">
                            <?php if (LanguageService::$language === $lang) : ?>
                                <span><?php echo strip_tags($langCode); ?></span>
                            <?php elseif (LanguageService::isLanguageSameAsFallback($lang)) : ?>
                                <a href="/">
                                    <?php echo strip_tags($langCode); ?>
                                </a>
                            <?php else : ?>
                                <a href="/?lang=<?php echo urlencode($lang); ?>">
                                    <?php echo strip_tags($langCode); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</footer>