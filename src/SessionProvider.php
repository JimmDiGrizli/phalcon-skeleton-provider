<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Session\Adapter\Files;

/**
 * Class SessionProvider
 * @package GetSky\Phalcon\Provider
 */
class SessionProvider implements Provider
{

    /**
     * @return callable
     */
    public function getServices()
    {
        return function () {
            $session = new Files();
            $session->start();
            return $session;
        };
    }
}
