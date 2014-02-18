<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Mvc\Url;

/**
 * Class UrlProvider
 * @package GetSky\Phalcon\Provider
 */
class UrlProvider implements Provider
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
        $this->options = $options;
    }

    /**
     * @return callable
     */
    public function getServices()
    {
        $default = $this->options->get('app')->get('base_uri');

        return function () use ($default) {
            $url = new Url();
            $url->setBaseUri($default);
            return $url;
        };
    }
}
