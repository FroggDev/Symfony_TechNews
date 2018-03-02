<?php
/**
 * Created by PhpStorm.
 * User: Remy
 * Date: 02/03/2018
 * Time: 02:10
 */

namespace App\Common\Util;


Trait SlugifyTrait
{
    /**
     * @param $text
     * @return null|string|string[]
     */
    public function slugify(string $text) : string
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

}