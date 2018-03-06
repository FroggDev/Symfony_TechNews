<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 06/03/2018
 * Time: 10:21
 */

namespace App\Common\Util;

class WordTag
{
    const EXCLUDEDWORDS = [
        'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'height', 'nine', 'ten',
        'million', 'thousand', 'hundred',
        'yes', 'no',
        'a', 'e', 'i', 'o', 'u', 'y',
        'you', 'he', 'she', 'we', 'they',
        'my', 'mine', 'your', 'yours', 'him', 'her', 'hers', 'our', 'them',
        'with',
        'monday', 'tuesday', 'wensday', 'thursday', 'friday', 'saturday', 'sunday',
        'now', 'tomorow',
        'to', 'from', 'for', 'in', 'on', 'of', 'off',
        'a', 'an', 'the',
        'be', 'is', 'will',
        'and', 'so',
        'going', 'being',
        'little', 'big',
        'change', 'ways', 'tip', 'says'
    ];

    public function getWordTags(array $sentenceList) : array
    {

        # collect words & weight
        $wordCollector = [];

        foreach ($sentenceList as $sentence) {
            # get all words from titles
            $articleWords = explode(" ", $sentence);

            foreach ($articleWords as $word) {
                #set words to lowercase
                $word = strtolower($word);
                # skip numeric words
                if (is_numeric($word) || in_array($word, $this::EXCLUDEDWORDS)) {
                    continue;
                }
                # add to word list or increment
                isset($wordCollector[$word]) ?
                    $wordCollector[$word]++ :
                    $wordCollector[$word] = 0;
            }
        }

        #order by weight
        arsort($wordCollector);

        # 10 first result
        $wordCollector = array_slice($wordCollector, 0, 10);

        return $wordCollector;
    }
}
