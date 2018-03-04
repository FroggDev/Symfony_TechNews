<?php
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
            $articles = Yaml::parseFile(__DIR__ . '/../../../tests/articles.yaml');
            return $articles['data'];
        } catch (ParseException $e) {
            printf('Unable to parse the YAML string: %s', $e->getMessage());
        }
        return [];
    }
}
