<?php
namespace GetSky\Phalcon\Provider;

use GetSky\Phalcon\AutoloadServices\Provider;
use PDO;
use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql;

class MySqlProvider implements Provider
{
    /**
     * @var Config
     */
    private $options;

    public function __construct(Config $options)
    {
        $this->options = $options
            ->get('db')
            ->get('mysql');
    }

    /**
     * @return callable
     */
    public function getServices()
    {
        $option = $this->options;

        return function () use ($option) {
            $mysql = new Mysql(
                [
                    'host' => $option->get('host', 'localhost'),
                    'username' => $option->get('username', 'root'),
                    'password' => $option->get('password', ''),
                    'persistent' => $option->get('persistent', true),
                    'dbname' => $option->get('name', 'db'),
                    'options' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                ]
            );

            return $mysql;
        };
    }
}
