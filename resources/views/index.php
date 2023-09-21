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
use App\Service\TemplateService;
?>

<?php TemplateService::renderPartials(array(
    '_start',
    '_header'
)); ?>

<main>
    <div class="container">
        <div class="row">
            <div id="e-collection-tab" class="tab-content col-12 <?php echo isset($_GET['tab']) && $_GET['tab'] !== 'collection' ? 'd-none' : ''; ?>">
                <div class="h1">
                    <span>
                        <?php echo LanguageService::getLabel('collection_title'); ?>
                    </span>
                    <div class="action-list">
                        <a id="collection-check-links" href="javascript:void(0);" class="btn btn-primary d-inline-block">
                            <?php echo LanguageService::getLabel('action_check_links'); ?>
                        </a>
                        <a id="collection-clear-cache" href="javascript:void(0);" class="btn btn-primary d-inline-block">
                            <?php echo LanguageService::getLabel('action_clear_cache'); ?>
                        </a>
                    </div>
                </div>
                <div id="e-collection-record-list">
                    <?php if (false !== $cachedECollections) : ?>
                        <?php TemplateService::renderPartial('e-collection/_e-collection-record-list', ['invalidElectronicCollections' => $cachedECollections]) ?>
                    <?php else : ?>
                        <p>
                            <?php echo LanguageService::getLabel('notice_no_action_performed'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div id="e-portfolio-tab" class="tab-content col-12 <?php echo (isset($_GET['tab']) && $_GET['tab'] !== 'portfolio') || !isset($_GET['tab']) ? 'd-none' : ''; ?>">
                <div class="h1">
                    <?php echo LanguageService::getLabel('portfolio_title'); ?>
                    <div class="action-list">
                        <a id="portfolio-check-links" href="javascript:void(0);" class="btn btn-primary d-inline-block">
                            <?php echo LanguageService::getLabel('action_check_links'); ?>
                        </a>
                        <a id="portfolio-clear-cache" href="javascript:void(0);" class="btn btn-primary d-inline-block">
                            <?php echo LanguageService::getLabel('action_clear_cache'); ?>
                        </a>
                    </div>
                </div>
                <div id="e-portfolio-record-list">
                    <?php if (false !== $cachedEPortfolios) : ?>
                        <?php TemplateService::renderPartial('e-portfolio/_e-portfolio-record-list', ['invalidElectronicPortfolios' => $cachedEPortfolios]) ?>
                    <?php else : ?>
                        <p>
                            <?php echo LanguageService::getLabel('notice_no_action_performed'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php TemplateService::renderPartials(array(
    '_footer',
    '_end'
)); ?>