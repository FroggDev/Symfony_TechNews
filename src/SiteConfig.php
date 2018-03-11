<?php
namespace App;

/**
 * Class SiteConfig
 * @package App
 */
class SiteConfig
{
    ###########
    # ARTICLE #
    ###########

    /**
     * nb article to display per page
     * @const int
     */
    const NBARTICLEPERPAGE=5;

    /**
     * nb article SUGESTION
     * @const int
     */
    const NBARTICLESUGESTION=9;

    /**
     * nb article sptolight
     * @const int
     */
    const NBARTICLESPOTLIGHT=5;

    /**
     * nb article special
     * @const int
     */
    const NBARTICLESPECIAL=1;

    ##############
    # NEWSLETTER #
    ##############

    /**
     * nb page displayed before newsletter popin
     * @const int
     */
    const NBPAGEBEFORENEWSLETTERPER=1;

    #################
    # YAML DATABASE #
    #################

    /**
     * name of the php cached file
     * @const string
     */
    const YAMLFILE = 'articles.yaml';

    /**
     * name of the php cached file in var/cache/(+dev on dev env)
     * @const string
     */
    const YAMLCACHEFILE = 'yaml-articles.php';

    ##########
    # LOCALE #
    ##########

    /**
     * Cookie validity in days
     * @const int
     */
    const COOKIELOCALEVALIDITY = 30;

    /**
     * Cookie name
     * @const string
     */
    const COOKIELOCALENAME = "locale";

}
