<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use GetSky\Phalcon\Bootstrap\Module;
use Phalcon\Config;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;

/**
 * Class ViewProvider
 * @package GetSky\Phalcon\Provider
 */
class ViewProvider implements Provider
{
    /**
     * @var Config
     */
    private $options;

    /**
     * @var string
     */
    private $class;

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
        $moduleClass = substr($options->get('bootstrap')->get('module'), 0, -4);
        $this->options = $moduleConfig->get('config')->get('view');
        $this->class = $moduleConfig->get('namespace') . '\\' . $moduleClass;
    }

    /**
     * @return callable
     */
    public function getServices()
    {
        $config = $this->options;
        $class = $this->class;

        return function () use ($config, $class) {
            $view = new View();
            /**
             * @var $class Module
             */
            $view->setViewsDir($class::DIR . '/Resources/views/');

            $view->registerEngines(
                [
                    '.volt' => function ($view) use ($config) {
                        $volt = new Volt($view);

                        $options = [
                            'compiledPath' => $config->get('path'),
                            'compiledSeparator' => '_',
                        ];

                        if ($config->get('debug') == true) {
                            $options['compileAlways'] = true;
                        }

                        $volt->setOptions($options);

                        return $volt;
                    },
                    '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
                ]
            );

            return $view;
        };
    }
}
