<?php
namespace App\Common\Traits\String;

trait MaxLengthTrait
{
    /**
     * @param string $text
     * @param int $size
     * @param bool $stripTags
     * @return string
     */
    public function maxLength(string $text, int $size, $stripTags = true): string
    {
        $string = $stripTags ? strip_tags($text) : $text;

        if (strlen($string) > $size) {
            $stringCut = substr($string, 0, $size);
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . "...";
        }

        return $string;
    }
}
