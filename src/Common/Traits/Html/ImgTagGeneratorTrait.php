<?php

namespace App\Common\Traits\Html;

trait ImgTagGeneratorTrait
{
    /**
     * @param string $src
     * @param string|null $alt
     * @param string|null $class
     * @param string|null $title
     * @param string|null $extraStuff
     * @return string
     */
    public function getImgTag(
        string $src,
        string $alt = null,
        string $class = null,
        string $title = null,
        string $extraStuff = null
    ): string {

        $title=$title??$alt;

        $textClass      = $class ? " class=\"$class\"" : "";
        $textAlt        = $alt ? " alt=\"$alt\"" : "";
        $textTitle      = $title ? " title=\"$title\"" : "";
        $textExtraStuff = $extraStuff ? " $extraStuff" : "";

        return "<img src=\"$src\"$textClass$textAlt$textTitle$textExtraStuff/>";
    }
}
