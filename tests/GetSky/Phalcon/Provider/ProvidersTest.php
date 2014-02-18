<?php

use GetSky\Phalcon\Provider\RouterProvider;
use GetSky\Phalcon\Provider\SessionProvider;
use GetSky\Phalcon\Provider\UrlProvider;
use Phalcon\Config;
use Phalcon\Mvc\Url;


class ProvidersTest  extends PHPUnit_Framework_TestCase {

    public function testIsProvider()
    {
        $router = new RouterProvider(new Config(
            array(
                "app"=>array("def_module"=>"frontend"),
                "modules"=>array("frontend"=>"frontend")
            )
        )
        );
        $session = new SessionProvider();
        $url = new UrlProvider(
            new Config(array("app"=>array("base_uri"=>"index")))
        );

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

        $service = $url->getServices();
        /**
         * @var $url Url
         */
        $url = $service();
        $this->assertSame("index", $url->getBaseUri());

        $service = $session->getServices();
        $this->assertInstanceOf('\Phalcon\Session\Adapter\Files', $service());

        $service = $router->getServices();
        $this->assertInstanceOf('\Phalcon\Mvc\Router', $service());

    }

} 