<?php

namespace App\Routers;

use App\Controllers\BrandsController;
use App\Controllers\CaixasController;
use App\Controllers\CategoryController;
use App\Controllers\ClientsController;
use App\Controllers\CompanyController;
use App\Controllers\ContasController;
use App\Controllers\ContasPagarReceberController;
use App\Controllers\ProductsController;
use App\Controllers\SubcategoryController;
use App\Controllers\SupplierController;
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

    $router->mount('/supplier', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $supplierController = new SupplierController();
        $supplierController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $supplierController = new SupplierController();
        $supplierController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $supplierController = new SupplierController($id);
        $supplierController->update($data);
      });
    });

    $router->mount('/clients', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $clientController = new ClientsController();
        $clientController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $clientController = new ClientsController();
        $clientController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $clientController = new ClientsController($id);
        $clientController->update($data);
      });
    });

    $router->mount('/products', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $productsController = new ProductsController();
        $productsController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $productsController = new ProductsController();
        $productsController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $productsController = new ProductsController($id);
        $productsController->update($data);
      });
    });

    $router->mount('/contas', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $contasController = new ContasController();
        $contasController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $contasController = new ContasController();
        $contasController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $contasController = new ContasController($id);
        $contasController->update($data);
      });
    });

    $router->mount('/finance', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $contasController = new ContasPagarReceberController();
        $contasController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $contasController = new ContasPagarReceberController();
        $contasController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $contasController = new ContasPagarReceberController($id);
        $contasController->update($data);
      });
    });

    $router->mount('/caixas', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $caixasController = new CaixasController();
        $caixasController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $caixasController = new CaixasController();
        $caixasController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $caixasController = new CaixasController($id);
        $caixasController->update($data);
      });
    });

    $router->run($callback);
  }
}
