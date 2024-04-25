<?php

namespace App\Routers;

use App\Controllers\BalanceController;
use App\Controllers\BrandsController;
use App\Controllers\CaixasController;
use App\Controllers\CategoryController;
use App\Controllers\ClientsController;
use App\Controllers\CompanyController;
use App\Controllers\ContasController;
use App\Controllers\ContasPagarReceberController;
use App\Controllers\InputsController;
use App\Controllers\ProductsController;
use App\Controllers\SalesController;
use App\Controllers\SalesProductsController;
use App\Controllers\SubcategoryController;
use App\Controllers\SupplierController;
use App\Controllers\UsersController;
use App\Controllers\ContaController;
use App\Controllers\PermissoesSistemaController;
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

    $router->mount('/sales', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $salesController = new SalesController();
        $salesController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $salesController = new SalesController();
        $salesController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $salesController = new SalesController($id);
        $salesController->update($data);
      });

      $router->mount('/products', function () use ($router) {
        $router->post('/list', function () {
          $data = json_decode(file_get_contents('php://input'), true);
          $salesController = new SalesProductsController();
          $salesController->find($data);
        });

        $router->post('/insert', function () {
          $data = json_decode(file_get_contents('php://input'), true);
          $salesController = new SalesProductsController();
          $salesController->create($data);
        });

        $router->put('/update/{id}', function ($id) {
          $data = json_decode(file_get_contents('php://input'), true);
          $salesController = new SalesProductsController($id);
          $salesController->update($data);
        });

        $router->delete('/delete/{id}', function ($id) {
          json_decode(file_get_contents('php://input'), true);
          $salesController = new SalesProductsController($id);
          $salesController->delete();
        });
      });
    });

    $router->mount('/balance', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $balanceController = new BalanceController();
        $balanceController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $balanceController = new BalanceController();
        $balanceController->create($data);
      });
    });

    $router->mount('/inputs', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $inputsController = new InputsController();
        $inputsController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $inputsController = new InputsController();
        $inputsController->create($data);
      });
    });

    $router->mount('/conta', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $contaController = new ContaController();
        $contaController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $contaController = new ContaController();
        $contaController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $contaController = new ContaController($id);
        $contaController->update($data);
      });
    });

    $router->mount('/log', function () use ($router) {
      $router->post('/lastpass', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $userController = new UsersController();
        $userController->setLastPass($data);
      });
    });

    $router->mount('/permission', function () use ($router) {
      $router->post('/list', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $permissoesSistemaController = new PermissoesSistemaController();
        $permissoesSistemaController->find($data);
      });

      $router->post('/insert', function () {
        $data = json_decode(file_get_contents('php://input'), true);
        $permissoesSistemaController = new PermissoesSistemaController();
        $permissoesSistemaController->create($data);
      });

      $router->put('/update/{id}', function ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $permissoesSistemaController = new PermissoesSistemaController($id);
        $permissoesSistemaController->update($data);
      });
    });

    $router->run($callback);
  }
}
