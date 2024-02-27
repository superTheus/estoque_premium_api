<?php

namespace App\Routers;

use App\Controllers\BrandsController;
use App\Controllers\CategoryController;
use App\Controllers\CompanyController;
use App\Controllers\SubcategoryController;
use App\Controllers\UsersController;
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

    $router->mount('/user', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $userController = new UsersController();
        $userController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $userController = new UsersController();
        $userController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $userController = new UsersController($id);
        $userController->update($data);
      });
    });

    $router->mount('/company', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $userController = new CompanyController();
        $userController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $userController = new CompanyController();
        $userController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $userController = new CompanyController($id);
        $userController->update($data);
      });
    });

    $router->mount('/brands', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $brandsController = new BrandsController();
        $brandsController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $brandsController = new BrandsController();
        $brandsController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $brandsController = new BrandsController($id);
        $brandsController->update($data);
      });
    });

    $router->mount('/categorys', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $categorysController = new CategoryController();
        $categorysController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $categorysController = new CategoryController();
        $categorysController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $categorysController = new CategoryController($id);
        $categorysController->update($data);
      });
    });

    $router->mount('/subcategorys', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $subcategorysController = new SubcategoryController();
        $subcategorysController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $subcategorysController = new SubcategoryController();
        $subcategorysController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $subcategorysController = new SubcategoryController($id);
        $subcategorysController->update($data);
      });
    });

    $router->run($callback);
  }
}
