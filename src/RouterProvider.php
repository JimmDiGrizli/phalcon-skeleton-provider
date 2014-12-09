<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Mvc\Router;

/**
 * Class RouterProvider
 * @package GetSky\Phalcon\Provider
 */
class RouterProvider implements Provider
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
        $default = $this->options->get('app')->get('def_module');
        $modules = $this->options->get('modules');

        return function () use ($default, $modules) {

            $router = new Router();
            $router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);

            foreach ($modules as $name => $module) {
                if ($default == $name) {
                    $router->setDefaultModule($default);
                    continue;
                }

                $router->add(
                    '#^/' . $name . '(|/)$#',
                    [
                        'module' => $name,
                        'controller' => 'index',
                        'action' => 'index',
                    ]
                );

                $router->add(
                    '#^/' . $name . '/([a-zA-Z0-9\_]+)[/]{0,1}$#',
                    [
                        'module' => $name,
                        'controller' => 1,
                    ]
                );

                $router->add(
                    '#^/' . $name . '[/]{0,1}([a-zA-Z0-9\_]+)/([a-zA-Z0-9\_]+)(/.*)*$#',
                    [
                        'module' => $name,
                        'controller' => 1,
                        'action' => 2,
                        'params' => 3,
                    ]
                );
            }

            return $router;
        };
    }
}
