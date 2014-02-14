<?php

use GetSky\Phalcon\Provider\RouterProvider;
use GetSky\Phalcon\Provider\SessionProvider;
use GetSky\Phalcon\Provider\UrlProvider;
use Phalcon\Config;

class ProvidersTest  extends PHPUnit_Framework_TestCase {

    public function testIsProvider()
    {
        $router = new RouterProvider(new Config());
        $session = new SessionProvider();
        $url = new UrlProvider(new Config());

        $this->assertInstanceOf(
            'GetSky\Phalcon\AutoloadServices\Provider',
            $router
        );

        $this->assertInstanceOf(
            'GetSky\Phalcon\AutoloadServices\Provider',
            $session
        );

        $this->assertInstanceOf(
            'GetSky\Phalcon\AutoloadServices\Provider',
            $url
        );

    }

} 