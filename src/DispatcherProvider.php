<?php
namespace  GetSky\Phalcon\Provider;

use Exception;
use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;

/**
 * Class DispatcherProvider
 * @package  GetSky\Phalcon\Provider
 */
class DispatcherProvider implements Provider
{
    /**
     * @var Config
     */
    private $options;
    /**
     * @var string
     */
    private $baseNamespace;

    /**
     * @param Config $options
     * @param string $baseNamespace
     */
    public function __construct(Config $options, $baseNamespace)
    {
        $this->options = $options;
        $this->baseNamespace = $baseNamespace;
    }

    /**
     * @return callable
     */
    public function getServices()
    {
        $option = $this->options->get('errors')->get('404');
        $baseNamespace = $this->baseNamespace;

        return function () use ($option, $baseNamespace) {

            $eventsManager = new Manager();

            $eventsManager->attach(
                "dispatch:beforeException",
                function ($event, $dispatcher, $exception) use ($option) {
                    /**
                     * @var $exception Exception
                     * @var $dispatcher Dispatcher
                     * @var $option Config
                     */
                    switch ($exception->getCode()) {
                        case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(
                                [
                                    'controller' => $option->get('controller'),
                                    'action' => $option->get('action')
                                ]
                            );
                    }
                    return false;
                }
            );

            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace($baseNamespace);
            return $dispatcher;
        };
    }
}
