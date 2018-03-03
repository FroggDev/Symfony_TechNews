<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 27/02/2018
 * Time: 15:50
 */

namespace App\Service\Article;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class ArticleProvider
{
    /**
     * @return array
     */
    public function getArticles(): array
    {
        try {
            $articles = Yaml::parseFile(__DIR__ . '/../../Service/articles.yaml');
            return $articles['data'];
        } catch (ParseException $e) {
            printf('Unable to parse the YAML string: %s', $e->getMessage());
        }
        return [];
    }
}
