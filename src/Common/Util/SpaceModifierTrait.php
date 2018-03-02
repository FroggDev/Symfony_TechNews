<?php
/**
 * Created by PhpStorm.
 * User: Remy
 * Date: 02/03/2018
 * Time: 05:14
 */

namespace App\Common\Util;


Trait SpaceModifierTrait
{
    public function spaceToUnderscore($text): string
    {
        return preg_replace('/ /', '_', $text);
    }

    public function underscoreToSpace($text): string
    {
        return preg_replace('/_/', ' ', $text);
    }

    public function traitToSpace($text): string
    {
        return preg_replace('/-/', ' ', $text);
    }

    public function spaceTotrait($text): string
    {
        return preg_replace('/ /', '-', $text);
    }

}