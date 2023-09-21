<?php

/*
 * This file is part of the Link Checker package.
 *
 * (c) ZHAW HSB <apps.hsb@zhaw.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

/**
 * Class AlmaService
 * @package App\Service
 */
class AlmaService
{

    /**
     * @var ALMA_API_KEY
     * @link https://developers.exlibrisgroup.com/manage/keys/
     */
    private string $almaApiKey = ALMA_API_KEY;


    /**
     * Retrieve electronic collections
     * @param string $url         The Url to retrieve eCollection data
     * @return array|null
     */
    public function retrieveECollectionByUrl(string $url): array|null
    {
        $ch = curl_init($url . '?apikey=' . $this->almaApiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($response, true);
        }

        return null;
    }

    /**
     * Update electronic collection
     * @param int $collectionId         The collection to update
     * @param string $publicName        The public name of the collection
     * @param array $type               The type of the collection
     * @param string $newUrl            The url to update the collection with
     * @param bool $isUrlOverride       Check if there is a static override url
     * @return true|null
     */
    public function updateECollectionById(int $collectionId, string $publicName, array $type, string $newUrl, bool $isUrlOverride): bool|null
    {

        $postArray = array(
            'public_name' => $publicName,
            'type' => $type,
        );

        if (!$isUrlOverride) {
            $postArray['url'] = $newUrl;
        }

        if ($isUrlOverride) {
            $postArray['url_override'] = $newUrl;
        }

        $ch = curl_init('https://api-eu.hosted.exlibrisgroup.com/almaws/v1/electronic/e-collections/' . $collectionId . '?apikey=' . $this->almaApiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode($postArray)
        );

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return true;
        }

        return null;
    }

    /**
     * Retrieve electronic collections
     * @param int $limit           Collections per request (0-100)
     * @param int $offset          Collections per page (0-100)
     * @return array|null
     */
    public function retrieveECollections(int $limit = 100, int $offset = 0): array|null
    {
        $ch = curl_init('https://api-eu.hosted.exlibrisgroup.com/almaws/v1/electronic/e-collections?apikey=' . $this->almaApiKey . '&limit=' . $limit . '&offset=' . $offset);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($response, true);
        }

        return null;
    }

    /**
     * Retrieve electronic services by collection id
     * @param int $collectionId      The collection id
     * @return array|null
     */
    public function retrieveEServicesByCollectionId(int $collectionId): array|null
    {
        $ch = curl_init('https://api-eu.hosted.exlibrisgroup.com/almaws/v1/electronic/e-collections/' . $collectionId . '/e-services?apikey=' . $this->almaApiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($response, true);
        }

        return null;
    }

    /**
     * Update electronic portfolio
     * @param int $collectionId         The collection id
     * @param int $serviceId            The service id
     * @param int $portfolioId          The portfolio id
     * @param string $newUrl            The url to update the collection with
     * @param bool $isUrlOverride       Check if there is a static override url
     * @return true|null
     */
    public function updateEPortfolioById(int $collectionId, int $serviceId, int $portfolioId, string $newUrl, bool $isUrlOverride): bool|null
    {
        $postArray = array();

        if (!$isUrlOverride) {
            $postArray['linking_details']['url_type']['value'] = 'static';
            $postArray['linking_details']['static_url'] = 'jkey=' . $newUrl;
        }

        if ($isUrlOverride) {
            $postArray['linking_details']['url_type_override']['value'] = 'static';
            $postArray['linking_details']['static_url_override'] = 'jkey=' . $newUrl;
        }

        $ch = curl_init('https://api-eu.hosted.exlibrisgroup.com/almaws/v1/electronic/e-collections/' . $collectionId . '/e-services/' . $serviceId . '/portfolios/' . $portfolioId . '?apikey=' . $this->almaApiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode(
                $postArray
            )
        );
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return true;
        }

        return null;
    }

    /**
     * Retrieve electronic portfolios by url
     * @param string $url         The Url to retrieve portfolio data
     * @return array|null
     */
    public function retrieveEPortfoliosByUrl(string $url): array|null
    {
        $ch = curl_init($url . '?apikey=' . $this->almaApiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($response, true);
        }

        return null;
    }

    /**
     * Retrieve electronic portfolio by url
     * @param string $url         The Url to retrieve a single portfolio
     * @return array|null
     */
    public function retrieveEPortfolioByUrl(string $url): array|null
    {
        $ch = curl_init($url . '?apikey=' . $this->almaApiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            return json_decode($response, true);
        }

        return null;
    }
}
