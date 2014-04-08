<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use GetSky\Phalcon\ConfigLoader\ConfigLoader;
use Phalcon\Config;
use Phalcon\Translate\Adapter\NativeArray;

/**
 * Class LoggerProvider
 * @package GetSky\Phalcon\Provider
 */
class TranslationProvider implements Provider
{

    /**
     * @var Config
     */
    private $options;

    /**
     * @var ConfigLoader
     */
    private $configLoader;

    /**
     * @var string
     */
    private $language;

    /**
     * @param Config $options
     * @param ConfigLoader $configLoader
     * @param string $language
     */
    public function __construct(Config $options, ConfigLoader $configLoader, $language)
    {
        $this->options = $options;
        $this->configLoader = $configLoader;
        $this->language = $language;
    }

    /**
     * @return callable
     */
    public function getServices()
    {
        return function () {
            $base = $this->options->get('translation')->get('base');
            $dir = $this->options->get('translation')->get('dir');
            $file = $this->options->get('translation')->get($this->language, null);

            if ($file === null) {
                $file = $this->options->get('translation')->get($base, null);

                if ($file === null) {
                    throw new \Exception("Not found translate config for language '{$base}' or '{$this->language}' ");
                }
            }

            if (file_exists($dir . $file)) {
                $translate = $this->configLoader->create($dir . $file);
            } else {
                throw new \Exception("Not found file '{$dir}{$file}' ");
            }

            return new NativeArray(["content" => $translate->toArray()]);
        };
    }
}
