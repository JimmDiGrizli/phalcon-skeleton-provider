<?php

use GetSky\Phalcon\Provider\DispatcherProvider;
use GetSky\Phalcon\Provider\LoggerProvider;
use GetSky\Phalcon\Provider\RouterProvider;
use GetSky\Phalcon\Provider\SessionProvider;
use GetSky\Phalcon\Provider\UrlProvider;
use Phalcon\Config;
use Phalcon\Mvc\Url;


class ProvidersTest  extends PHPUnit_Framework_TestCase {

    public function testIsProvider()
    {
        $router = new RouterProvider(
            new Config(
                [
                    "app" => ["def_module" => "frontend"],
                    "modules" => ["frontend" => "frontend"]
                ]
            )
        );
        $session = new SessionProvider();
        $url = new UrlProvider(
            new Config(["app" => ["base_uri" => "index"]])
        );
        $dispatcher = new DispatcherProvider(
            new Config(
                [
                    "errors" => [
                        "404" => [
                            "controller" => "test",
                            "action" => "test"
                        ]
                    ]
                ]
            ),
            '/GetSky/TestControllers/'
        );

        $logger = new LoggerProvider(
            new Config(
                [
                    "logger" => [
                        'adapter' => '\Phalcon\Logger\Adapter\File',
                        'path' => 'error.log',
                        'format' => '[%date%][%type%] %message%'
                    ]
                ]
            )
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

        $this->assertInstanceOf(
            'GetSky\Phalcon\AutoloadServices\Provider',
            $dispatcher
        );

        $this->assertInstanceOf(
            'GetSky\Phalcon\AutoloadServices\Provider',
            $logger
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

        $service = $dispatcher->getServices();
        $this->assertInstanceOf('\Phalcon\Mvc\Dispatcher', $service());

        $service = $logger->getServices();
        $this->assertInstanceOf('\Phalcon\Logger\Adapter\File', $service());

    }
}
