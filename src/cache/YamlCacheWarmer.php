<?php
namespace App\cache;

use App\SiteConfig;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlCacheWarmer
 * @package App\cache
 */
class YamlCacheWarmer extends CacheWarmer
{

    /**
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return bool true if the warmer is optional, false otherwise
     */
    public function isOptional()
    {
        // TODO: Implement isOptional() method.
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        try {
            $articles = Yaml::parseFile(__DIR__ . '/../../' . SiteConfig::YAMLFILE);

            $this->writeCacheFile(
                $cacheDir.'/'.SiteConfig::YAMLCACHEFILE,
                serialize($articles['data'])
            );
        } catch (ParseException $e) {
            printf('Unable to parse the YAML file', $e->getMessage());
        }
    }
}
