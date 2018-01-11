<?php
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/projects', function ($routes) {
   $routes->connect('/', ['controller' => 'Projects', 'action' => 'index']);
   $routes->connect('/:id', ['controller' => 'Projects', 'action' => 'view']);
   $routes->connect('/:id/tasks',
       ['controller' => 'Projects', 'action' => 'tasks'],
       ['pass' => ['id']]
   );
});


Router::scope('/tasks', function ($routes) {
    $routes->connect('/', ['controller' => 'Tasks', 'action' => 'index']);
    $routes->connect('/:id', ['controller' => 'Tasks', 'action' => 'view']);
    $routes->connect('/:id/logs',
        ['controller' => 'Tasks', 'action' => 'logs'],
        ['pass' => ['id']]
    );
});


Router::scope('/taskstates', function ($routes) {
    $routes->connect('/', ['controller' => 'TaskStates', 'action' => 'index']);
});


Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->fallbacks(DashedRoute::class);
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
