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

use Exception;

/**
 * Class CacheService
 * @package App\Service
 */
class CacheService
{

    /**
     * @static RESULT_TYPE_ECOLLECTION
     */
    const RESULT_TYPE_ECOLLECTION = 'e-collection';

    /**
     * @static RESULT_TYPE_EPORTFOLIO
     */
    const RESULT_TYPE_EPORTFOLIO = 'e-portfolio';

    /**
     * @var array $allowedResultTypes
     */
    private static array $allowedResultTypes = array(
        'e-portfolio',
        'e-collection'
    );

    /**
     * Create cache directories
     * @return void
     */
    public static function createCacheDirs(): void
    {
        foreach (self::$allowedResultTypes as $resultType) {
            if (!file_exists(CACHE_DIR . '/' . $resultType)) {
                mkdir(CACHE_DIR . '/' . $resultType);
            }
        }
    }

    /**
     * Cache result
     * @param string $resultType    The result type
     * @param array $result         The result
     * @throws Exception $exception
     * @return bool int|false
     */
    public static function cacheResult(string $resultType, array $result, bool $override = false): int|false
    {
        if (!in_array($resultType, self::$allowedResultTypes)) {
            throw new Exception('Invalid cache result type: Cache result type can only be one of' . implode(',', self::$allowedResultTypes));
        }

        if ($override) {
            $cachedFile = glob(CACHE_DIR . '/' . $resultType . '/*');
            if (!empty($cachedFile)) {
                $firstCachedElementPath = reset($cachedFile);

                return file_put_contents($firstCachedElementPath, json_encode($result));
            }
        }

        return file_put_contents(CACHE_DIR . '/' . $resultType . '/' . $resultType . '_' . time() . '.json', json_encode($result));
    }

    /**
     * Check and get cached contents
     * @param string $folderName      The result type
     * @throws Exception $exception
     * @return array|false
     */
    public static function getCacheFile(string $resultType): array|false
    {
        if (!in_array($resultType, self::$allowedResultTypes)) {
            throw new Exception('Invalid cache result type: Cache result type can only be one of' . implode(',', self::$allowedResultTypes));
        }

        $cachedFile = glob(CACHE_DIR . '/' . $resultType . '/*');

        if (!empty($cachedFile)) {
            $firstCachedElementPath = reset($cachedFile);
            $cachedFileParts = explode('_', $firstCachedElementPath);

            if (time() < (substr(end($cachedFileParts), 0, -5) + CACHE_DURATION)) {
                return json_decode(
                    file_get_contents($firstCachedElementPath),
                    true
                );
            }

            unlink($firstCachedElementPath);
        }

        return false;
    }
    /**
     * Check and get cached contents
     * @param string $folderName      The result type
     * @throws Exception $exception
     * @return bool
     */
    public static function deleteCacheFile(string $resultType): bool
    {
        if (!in_array($resultType, self::$allowedResultTypes)) {
            throw new Exception('Invalid cache result type: Cache result type can only be one of' . implode(',', self::$allowedResultTypes));
        }

        $cachedFile = glob(CACHE_DIR . '/' . $resultType . '/*');

        if (!empty($cachedFile)) {
            $firstCachedElementPath = reset($cachedFile);
            return unlink($firstCachedElementPath);
        }

        return true;
    }
}
