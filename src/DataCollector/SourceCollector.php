<?php

namespace App\DataCollector;

use App\Service\Article\ArticleCatalog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class SourceCollector
 * @package App\DataColector
 */
class SourceCollector extends DataCollector
{
    private $catalog;

    /**
     * SourceCollector constructor.
     * @param ArticleCatalog $catalog
     */
    public function __construct(ArticleCatalog $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * Collects data for the given Request and Response.
     * @param Request $request
     * @param Response $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = $this->catalog->getStats();
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'app.source_collector';
    }

    /**
     * Resets this data collector to its initial state.
     */
    public function reset()
    {
        $this->data = [];
    }

    public function getStats()
    {
        return $this->data;
    }
}
