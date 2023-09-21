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
 * Class LanguageService
 * @package App\Service
 */
class LanguageService
{
    /** @var string $language */
    public static string $language = 'de';

    /**
     * Retrieve label of the default language
     * @param string $label     The label to lookup
     * @return string
     */
    public static function getLabel(string $label): string
    {
        $languageLabels = self::hasLanguage(self::$language) ? $GLOBALS['languages'][self::$language] : $GLOBALS['languages']['fallback'];

        return $languageLabels[$label] ?? $languageLabels['error_no_label_found'];
    }

    /**
     * Set current language
     * @param string $language   The current language code: de, en, fr, it
     * @return string
     */
    public static function setLanguage(string $language): void
    {
        self::$language = $language;
    }

    /**W
     * Check if configuration has language
     * @param string $language   The current language code: de, en, fr, it
     * @return bool
     */
    public static function hasLanguage(string $language): bool
    {
        return isset($GLOBALS['languages'][$language]);
    }

    /**
     * Check if current language is fallback language
     * @return bool
     */
    public static function isFallbackLanguage(): bool
    {
        return self::$language === $GLOBALS['languages']['fallback'];
    }

    /**
     * Check if selectables language is fallback language
     * @return bool
     */
    public static function isLanguageSameAsFallback(string $language): bool
    {
        return $language === $GLOBALS['languages']['fallback'];
    }
}
