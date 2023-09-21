<?php

/*
 * This file is part of the Link Checker package.
 *
 * (c) ZHAW HSB <apps.hsb@zhaw.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use App\Service\AlmaService;
use App\Service\CacheService;
use App\Service\LanguageService;
use App\Service\LinkService;
use App\Service\TemplateService;
use App\TraitRegistry\HttpTrait;
use Exception;

/**
 * Class Application
 * @package App
 */
class Application
{
    use HttpTrait;

    /**
     * @var AlmaService $almaService
     */
    private AlmaService $almaService;

    public function __construct()
    {
        if (empty(ALMA_API_KEY)) {
            throw new Exception('No Alma API key provided. Please set it in configuration.php');
        }

        if (isset($_GET['lang'])) {
            LanguageService::setLanguage(strip_tags($_GET['lang']));
        }

        $this->almaService = new AlmaService();
    }

    /**
     * Render start view
     */
    public function indexAction()
    {
        if ($this->isAjaxRequest()) {
            $this->handleCollectionAjaxRequest();
            $this->handlePortfolioAjaxRequest();
            return;
        }

        TemplateService::renderView(
            'index',
            [
                'cachedECollections' => CacheService::getCacheFile(CacheService::RESULT_TYPE_ECOLLECTION),
                'cachedEPortfolios' => CacheService::getCacheFile(CacheService::RESULT_TYPE_EPORTFOLIO)
            ]
        );
    }

    /**
     * Handles ajax submission for collections
     */
    private function handleCollectionAjaxRequest()
    {
        // On collection update event
        if (
            isset($_GET['collectionId']) &&
            isset($_GET['publicName']) &&
            isset($_GET['type']) &&
            isset($_GET['newUrl'])
        ) {
            $collectionId = intval($_GET['collectionId']);
            $publicName = strip_tags($_GET['publicName']);
            $typeArr = explode(',', $_GET['type']);
            $type = array(
                'value' => intval($typeArr[0]),
                'desc' => $typeArr[1] === 'Database' ? 'Database' : null
            );
            $isUrlOverride = (bool) $_GET['isUrlOverride'];
            $newUrl = strip_tags($_GET['newUrl']);

            if (
                LinkService::checkLink($newUrl) &&
                null !== $this->almaService->updateECollectionById($collectionId, $publicName, $type, $newUrl, $isUrlOverride)
            ) {
                if (false !== ($cachedFileData = CacheService::getCacheFile(CacheService::RESULT_TYPE_ECOLLECTION))) {
                    foreach ($cachedFileData as $key => $collection) {
                        if ($collection['id'] == $collectionId) {
                            unset($cachedFileData[$key]);
                            CacheService::cacheResult(CacheService::RESULT_TYPE_ECOLLECTION, $cachedFileData, true);
                        }
                    }
                }
                echo json_encode(array(
                    'code' => 200,
                    'message' => LanguageService::getLabel('message_success_collection_update')
                ));
                return;
            }

            echo json_encode(array(
                'code' => 400,
                'message' => LanguageService::getLabel('message_error_collection_update')
            ));
            return;
        }

        // On check links event
        if (
            isset($_POST['checkCollectionLinks']) &&
            $_POST['checkCollectionLinks'] === 'process'
        ) {
            echo TemplateService::renderPartial('e-collection/_e-collection-record-list', [
                'invalidElectronicCollections' => $this->getInvalidElectronicCollections()

            ], true, true);

            return;
        }

        // On clear cache event
        if (
            isset($_POST['clearCollectionCache']) &&
            $_POST['clearCollectionCache'] == true
        ) {
            if (CacheService::deleteCacheFile(CacheService::RESULT_TYPE_ECOLLECTION)) {
                echo json_encode(array(
                    'code' => 200,
                    'message' => LanguageService::getLabel('message_success_clear_collection_cache')
                ));
            } else {
                echo json_encode(array(
                    'code' => 400,
                    'message' => LanguageService::getLabel('message_error_clear_collection_cache')
                ));
            }
            return;
        }
    }

    /**
     * Handles ajax submission of portfolios
     */

    private function handlePortfolioAjaxRequest()
    {

        // On portfolio update event
        if (
            isset($_GET['collectionId']) &&
            isset($_GET['serviceId']) &&
            isset($_GET['portfolioId']) &&
            isset($_GET['newUrl'])
        ) {
            $collectionId = intval($_GET['collectionId']);
            $serviceId = intval($_GET['serviceId']);
            $portfolioId = intval($_GET['portfolioId']);
            $isUrlOverride = (bool) $_GET['isUrlOverride'];
            $newUrl = strip_tags($_GET['newUrl']);

            if (
                LinkService::checkLink($newUrl) &&
                null !== $this->almaService->updateEPortfolioById($collectionId, $serviceId, $portfolioId, $newUrl, $isUrlOverride)
            ) {
                if (false !== ($cachedFileData = CacheService::getCacheFile(CacheService::RESULT_TYPE_EPORTFOLIO))) {
                    foreach ($cachedFileData as $key => $portfolio) {
                        if ($portfolio['id'] == $portfolioId) {
                            unset($cachedFileData[$key]);
                            CacheService::cacheResult(CacheService::RESULT_TYPE_EPORTFOLIO, $cachedFileData, true);
                        }
                    }
                }
                echo json_encode(array(
                    'code' => 200,
                    'message' => LanguageService::getLabel('message_success_portfolio_update')
                ));

                return;
            }

            echo json_encode(array(
                'code' => 400,
                'message' => LanguageService::getLabel('message_error_portfolio_update')
            ));

            return;
        }

        // On check links event
        if (
            isset($_POST['checkPortfolioLinks']) &&
            $_POST['checkPortfolioLinks'] === 'process'
        ) {
            echo TemplateService::renderPartial('e-portfolio/_e-portfolio-record-list', [
                'invalidElectronicPortfolios' => $this->getInvalidElectronicPortfolios()
            ], true, true);

            return;
        }

        // On clear cache event
        if (
            isset($_POST['clearPortfolioCache']) &&
            $_POST['clearPortfolioCache'] == true
        ) {
            if (CacheService::deleteCacheFile(CacheService::RESULT_TYPE_EPORTFOLIO)) {
                echo json_encode(array(
                    'code' => 200,
                    'message' => LanguageService::getLabel('message_success_clear_portfolio_cache')
                ));
            } else {
                echo json_encode(array(
                    'code' => 400,
                    'message' => LanguageService::getLabel('message_error_clear_portfolio_cache')
                ));
            }

            return;
        }
    }

    /**
     * Get all electronic portfolios that have an inaccessible "url" field.
     * The field "url_override" will not be taken into account.
     */
    private function getInvalidElectronicCollections()
    {
        $paginatedRecordCount = 0;
        $iterationCounter = 1;
        $eCollectionArr = array();
        $eCollections = $this->almaService->retrieveECollections(100, 0);

        if (false !== ($cachedFileData = CacheService::getCacheFile(CacheService::RESULT_TYPE_ECOLLECTION))) {
            return $cachedFileData;
        }

        while ($paginatedRecordCount <= $eCollections['total_record_count']) {

            foreach ($eCollections['electronic_collection'] as $eCollectionRecord) {

                // Only local databases
                if ($eCollectionRecord['is_local'] === true && $eCollectionRecord['type']['desc'] === 'Database') {
                    $eCollectionAccessLink = $eCollectionRecord['link'];
                    $eCollectionData = $this->almaService->retrieveECollectionByUrl($eCollectionAccessLink);
                    $databaseUrl = $eCollectionData['url'];

                    if (!empty($eCollectionData['url']) && LinkService::checkLink($databaseUrl)) {
                        continue;
                    }

                    $eCollectionArr[] = $eCollectionData;
                }
            }

            $iterationCounter++;
            $paginatedRecordCount += 100;

            $eCollections = $this->almaService->retrieveECollections(100, $paginatedRecordCount);
        }

        CacheService::cacheResult(CacheService::RESULT_TYPE_ECOLLECTION, $eCollectionArr);

        return $eCollectionArr;
    }

    /**
     * Get all electronic portfolios that have an inaccessible "static_url_override" field.
     * The field "static_url" will not be taken into account.
     */
    private function getInvalidElectronicPortfolios()
    {

        $paginatedRecordCount = 0;
        $iterationCounter = 1;
        $ePortfolioArr = array();
        $eCollections = $this->almaService->retrieveECollections(100, 0);

        if (false !== ($cachedFileData = CacheService::getCacheFile(CacheService::RESULT_TYPE_EPORTFOLIO))) {
            return $cachedFileData;
        }

        while ($paginatedRecordCount <= $eCollections['total_record_count']) {

            foreach ($eCollections['electronic_collection'] as $eCollectionRecord) {

                // Skip if collection is not local or no portfolios attached...
                if ($eCollectionRecord['is_local'] === false || $eCollectionRecord['portfolios']['value'] == 0) {
                    continue;
                }

                $electronicServices = $this->almaService->retrieveEServicesByCollectionId($eCollectionRecord['id']);

                foreach ($electronicServices['electronic_service'] as $electronicServiceRecord) {

                    $portfolios = $this->almaService->retrieveEPortfoliosByUrl($electronicServiceRecord['portfolios']['link']);

                    foreach ($portfolios['portfolio'] as $portfolioRecord) {

                        $portfolio = $this->almaService->retrieveEPortfolioByUrl($portfolioRecord['link']);

                        // Only local portfolios
                        if ($portfolio['is_local'] === true) {
                            $staticUrl = str_replace('jkey=', '', $portfolio['linking_details']['static_url']);
                            $staticUrlOverride = str_replace('jkey=', '', $portfolio['linking_details']['static_url_override']);

                            // Ignore non-static urls
                            if (!empty($portfolio['linking_details']['url_type']['value']) && $portfolio['linking_details']['url_type']['value'] !== 'static') {
                                continue;
                            }

                            if (!empty($portfolio['linking_details']['url_type_override']['value']) && $portfolio['linking_details']['url_type_override']['value'] !== 'static') {
                                continue;
                            }

                            // Ignore collab links because they are managed by our staff
                            if (str_contains($staticUrl, 'collab.zhaw.ch') || str_contains($staticUrlOverride, 'collab.zhaw.ch')) {
                                continue;
                            }

                            // Check Links
                            if (!empty(trim($staticUrl)) && LinkService::checkLink($staticUrl)) {
                                continue;
                            }

                            if (!empty(trim($staticUrlOverride)) && LinkService::checkLink($staticUrlOverride)) {
                                continue;
                            }

                            $portfolio['electronic_collection_name'] = $eCollectionRecord['public_name'];
                            $ePortfolioArr[] = $portfolio;
                        }
                    }
                }
            }

            $iterationCounter++;
            $paginatedRecordCount += 100;

            $eCollections = $this->almaService->retrieveECollections(100, $paginatedRecordCount);
        }

        CacheService::cacheResult(CacheService::RESULT_TYPE_EPORTFOLIO, $ePortfolioArr);

        return $ePortfolioArr;
    }
}
