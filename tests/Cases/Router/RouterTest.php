<?php

/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 09/04/16 11:26
 */

namespace Phact\Tests;

use InvalidArgumentException;
use Phact\Application\Application;
use Phact\Helpers\Configurator;
use Phact\Helpers\Paths;
use Phact\Router\Route;
use Phact\Router\Router;

class RouterTest extends AppTest
{
    public function testCollectFromFile()
    {
        $router = new Router();
        $routesPath = Paths::file('base.config.routes', 'php');
        $routes = include $routesPath;
        $router->collect($routes);
        $this->assertEquals([[
            'GET|POST',
            '/test_route',
            [
                'Modules\Test\Controllers\TestController',
                'test'
            ],
            'test:test'
        ]], $router->getRoutes());
        $this->assertEquals('/test_route', $router->url('test:test'));
        $this->assertEquals([
            'target' => [
                'Modules\Test\Controllers\TestController',
                'test'
            ],
            'params' => [],
            'name' => 'test:test'
        ], $router->match('/test_route', 'GET'));
        $this->assertEquals('/test_route', $router->url('test:test'));
    }

    public function testParameterRoutes()
    {
        $router = new Router();
        $router->collect([
            new Route('/test1/{[0-9]+:id}', 'target', 'first-route'),
            new Route('/test2/{slug:name}', 'target', 'second-route'),
            new Route('/test3/{:name}', 'target', 'third-route')
        ]);

        $this->assertEquals([
            'target' => 'target',
            'params' => [
                'id' => '0102'
            ],
            'name' => 'first-route'
        ], $router->match('/test1/0102'));

        $this->assertEquals("/test1/123", $router->url('first-route', ['id' => 123]));
        $this->assertEquals("/test1/321", $router->url('first-route', [321]));

        $this->assertEquals([
            'target' => 'target',
            'params' => [
                'name' => 'amazing_route'
            ],
            'name' => 'second-route'
        ], $router->match('/test2/amazing_route'));

        $this->assertEquals("/test2/amazing_route", $router->url('second-route', ['name' => 'amazing_route']));
        $this->assertEquals("/test2/amazing_route", $router->url('second-route', ['amazing_route']));

        $this->assertEquals([
            'target' => 'target',
            'params' => [
                'name' => 'amazing_route'
            ],
            'name' => 'third-route'
        ], $router->match('/test3/amazing_route'));

        $this->assertEquals("/test3/amazing_route", $router->url('third-route', ['name' => 'amazing_route']));
        $this->assertEquals("/test3/amazing_route", $router->url('third-route', ['amazing_route']));

        return $router;
    }

    /**
     * @depends testParameterRoutes
     * @expectedException InvalidArgumentException
     * @param $router Router
     */
    public function testInvalidArgument($router)
    {
        $router->url('first-route');
    }
}