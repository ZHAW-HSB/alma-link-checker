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
<header>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <img src="<?php echo ASSET_DIR_HTTP; ?>/img/logo.png" />
                <a id="app-title" href="<?php echo BASE_PATH; ?>" class="h1">
                    <?php echo APP_NAME; ?>
                </a>
            </div>
            <div class="col-6">
                <nav id="main-navigation">
                    <ul class="list-unstyled">
                        <li class="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'collection') || !isset($_GET['tab']) ? 'active' : ''; ?>">
                            <a data-href="e-collection-tab" data-url="collection" href="?tab=collection<?php isset($_GET['lang']) ?>">
                                <?php echo LanguageService::getLabel('electronic_database_title') ?>
                            </a>
                        </li>
                        <li class="<?php echo isset($_GET['tab']) && $_GET['tab'] === 'portfolio' ? 'active' : ''; ?>">
                            <a data-href="e-portfolio-tab" data-url="portfolio" href="?tab=portfolio<?php echo isset($_GET['lang']) ? '&lang=' . urlencode($_GET['lang']) : ''; ?>">
                                <?php echo LanguageService::getLabel('electronic_portfolio_title') ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>