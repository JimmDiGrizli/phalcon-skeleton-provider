<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Cache\Backend\File;
use Phalcon\Cache\Frontend\Output;
use Phalcon\Config;

class ViewCacheProvider implements Provider
{
    /**
     * @var Config
     */
    private $options;

    /**
     * @param Config $options
     * @param string $module
     */
    public function __construct(Config $options, $module)
    {
        /**
         * @var $moduleConfig Config
         */
        $moduleConfig = $options->get('modules')->get($module);
        $this->options = $moduleConfig->get('config')->get('voltCache');
    }

    /**
     * @return callable
     */
    public function getServices()
    {
        $config = $this->options;

        return function () use ($config) {

            $frontCache = new Output(
                ["lifetime" => $config->get('lifetime', 86400)]
            );

            return new File(
                $frontCache,
                ["cacheDir" => $config->get('path')]
            );
        };
    }
}
