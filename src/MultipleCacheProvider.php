<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Cache\Backend\Apc;
use Phalcon\Cache\Backend\File;
use Phalcon\Cache\Backend\Memcache;
use Phalcon\Cache\Frontend\Data;
use Phalcon\Cache\Multiple;
use Phalcon\Config;

class MultipleCacheProvider implements Provider
{
    /**
     * @var Config
     */
    private $options;

    /**
     * @param Config $options
     */
    public function __construct(Config $options)
    {
        /**
         * @var $moduleConfig Config
         */
        $this->options = $options;
    }

    /**
     * @return callable
     */
    public function getServices()
    {
        /**
         * @var Config $config
         */
        $config = $this->options->get('cache');

        return function () use ($config) {

            $ultraFastFrontend = new Data(['lifetime' => 3600]);
            $fastFrontend = new Data(['lifetime' => 86400]);
            $slowFrontend = new Data(['lifetime' => 604800]);

            $cache = new Multiple(
                [
                    new Apc(
                        $ultraFastFrontend,
                        [$config->get('prefix')]
                    ),
                    new Memcache(
                        $fastFrontend,
                        [
                            'prefix' => $config->get('prefix'),
                            'host' => $config->get('host'),
                            'port' => $config->get('port')
                        ]
                    ),
                    new File(
                        $slowFrontend,
                        [
                            'prefix' => $config->get('prefix'),
                            'cacheDir' => $config->get('path')
                        ]
                    )
                ]
            );

            return $cache;
        };
    }
}
