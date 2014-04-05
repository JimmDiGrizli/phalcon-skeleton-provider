<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Logger\Formatter\Line;

/**
 * Class LoggerProvider
 * @package GetSky\Phalcon\Provider
 */
class LoggerProvider implements Provider
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
        return function () {
            $adapter = $this->options->get('logger')->get('adapter');
            $path = $this->options->get('logger')->get('path');
            $format = $this->options->get('logger')->get('format');

            /**
             * @var \Phalcon\Logger\Adapter\File $logger
             */
            $logger = new $adapter($path);
            $logger->setFormatter(new Line($format));
            return $logger;
        };
    }
}
