<?php
namespace App\Exception;

use App\Entity\Article;
use Throwable;

/**
 * Class DuplicateCatalogDataException
 * @package App\Exception
 */
class DuplicateCatalogDataException extends \RuntimeException
{
    private $article;

    /**
     * DuplicateCatalogDataException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param Article $article
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, Article $article)
    {
        parent::__construct($message, $code, $previous);

        $this->article = $article;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }
}
