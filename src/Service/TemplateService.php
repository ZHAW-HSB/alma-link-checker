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
 * Class TemplateService
 * @package App\Service
 */
class TemplateService
{

    /**
     * Render a given view by name
     * @param string $view      View name
     * @param array  $data      Key/Value based data to pass into the view
     * @param bool   $buffered  Enable/Disable output buffering
     * @param bool   $extract   Enable/Disable parameter exctraction in views
     */
    public static function renderView(string $view, array $data = array(), bool $buffered = false, bool $extract = true)
    {

        if ($extract) {
            extract($data, EXTR_SKIP);
        }

        if (!$buffered) {
            require(VIEW_DIR . DIRECTORY_SEPARATOR . $view . '.php');
            return;
        }

        ob_start();

        require(VIEW_DIR . DIRECTORY_SEPARATOR . $view . '.php');

        return ob_get_clean();
    }

    /**
     * Render a given partial by name
     * @param string $partial   Partial name
     * @param array  $data      Key/Value based data to pass into the partial view
     * @param bool   $buffered  Enable/Disable output buffering
     * @param bool   $extract   Enable/Disable parameter exctraction in partial views
     */

    public static function renderPartial(string $partial, array $data = array(), bool $buffered = false, bool $extract = true)
    {

        if ($extract) {
            extract($data, EXTR_SKIP);
        }

        if (!$buffered) {
            require(PARTIAL_DIR . DIRECTORY_SEPARATOR . $partial . '.php');
            return;
        }

        ob_start();

        require(PARTIAL_DIR . DIRECTORY_SEPARATOR . $partial . '.php');

        return ob_get_clean();
    }

    /**
     * Render multiple partials by name
     * @param array  $partials  Key/Value based partial names
     * @param array  $data      Key/Value based data to pass into the partial view
     * @param bool   $buffered  Enable/Disable output buffering
     * @param bool   $extract   Enable/Disable parameter exctraction in partial views
     */

    public static function renderPartials(array $partials, array $data = array(), bool $buffered = false, bool $extract = true)
    {
        $content = '';

        foreach ($partials as $partial) {

            if ($extract) {
                extract($data, EXTR_SKIP);
            }

            if (!$buffered) {
                require(PARTIAL_DIR . DIRECTORY_SEPARATOR . $partial . '.php');
                continue;
            }

            ob_start();

            require(PARTIAL_DIR . DIRECTORY_SEPARATOR . $partial . '.php');

            $content += ob_get_clean();
        }

        return $content;
    }
}
