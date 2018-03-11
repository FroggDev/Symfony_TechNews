<?php
namespace App\Common\Traits\Client;


/**
 * Trait BrowserTrait
 * @package App\Common\Traits\Client
 */
trait BrowserTrait
{
    /**
     * get the user browser language
     * @return string|null
     * @access private
     */
    private function getUserBrowserLangs() : ?string
    {
        preg_match_all('/([a-z]{2})-[A-Z]{2}/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang);
        if (count($lang)>0) {
            return $lang[1][0];
        }
        return null;
    }
}