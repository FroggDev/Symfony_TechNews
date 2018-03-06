<?php

namespace App\Common\Traits\Html;

trait ATagGeneratorTrait
{
    /**
     * @param string $href
     * @param string $text
     * @param string|null $class
     * @param string|null $target
     * @param string|null $extraStuff
     * @param bool $notADownload
     * @return string
     */
    public function getATag(
        string $href,
        string $text,
        string $class = null,
        string $target = null,
        string $extraStuff = null,
        bool $notADownload = true
    ): string {
        $textClass      = $class ? " class=\"$class\"" : "";
        $textTarget     = $target ? " target=\"$target\"" : "";
        $textExtraStuff = $extraStuff ? " $extraStuff" : "";
        $textDownload   = $notADownload ?? " download";

        return "<a href=\"$href\"$textClass$textTarget$textExtraStuff$textDownload>$text</a>";
    }
}
