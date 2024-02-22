<?php

namespace App\Routers;

use Bramus\Router\Router;

class Routers
{
  public static function execute($callback = null)
  {
    $router = new Router();

    $router->set404(function () {
      header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
      echo '404, Rota não encontrada';
    });

    // Before Router Middleware
    $router->before('GET', '/.*', function () {
      header('X-Powered-By: bramus/router');
    });

    // Static route: / (homepage)
    $router->get('/', function () {
      echo 'Home';
    });

    $router->run($callback);
  }
}
