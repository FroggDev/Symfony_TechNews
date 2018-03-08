<?php
namespace App\Service\Article;

use App\SiteConfig;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ArticleProvider
 * @package App\Service\Article
 */
class YamlProvider
{

    private $kernel;

    /**
     * ArticleProvider constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel=$kernel;
    }

    /**
     * @return array
     */
    public function getArticles(): array
    {
        return unserialize(
            file_get_contents(
                $this->kernel->getCacheDir().'/'.SiteConfig::YAMLCACHEFILE
            )
        );
    }
}
