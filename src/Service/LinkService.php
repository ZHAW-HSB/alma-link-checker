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
 * Class LinkService
 * @package App\Service
 */

class LinkService
{

    /**
     * Check if url is valid (return status of GET request should be 200)
     * @param string $url       The url to check
     * @return bool
     */
    public static function checkLink(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpcode === 200 ? true : false;
    }
}
