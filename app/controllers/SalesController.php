<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\ProductsModel;
use App\Models\SalePayFormsModel;
use App\Models\SalesModel;
use App\Models\SalesProductsModel;
use App\Models\UserModel;

class SalesController
{
  protected $salesModel;

  public function __construct($id = null)
  {
    $this->salesModel = new SalesModel($id ? $id : null);
  }

  public function find($data)
  {
    $salesModel = new SalesModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $salesModel->find($filter, $limit);

    if ($results) {
      foreach ($results as $key => $result) {
        $userModel = new UserModel($result->id_user);
        $results[$key]->user = $userModel->getCurrentUser();

        $clientModel = new ClientsModel($result->id_client);
        $results[$key]->client = $clientModel->getCurrentClient();

        $salesProducts = new SalesProductsModel();
        $results[$key]->products = $salesProducts->find([
          "id_sale" => $result->id
        ]);

        foreach ($results[$key]->products as $k => $product) {
          $productModel = new ProductsModel($product->id_product);
          $results[$key]->products[$k]->product = $productModel->getCurrentProduct();
        }
      }
    }

    if ($results) {
      http_response_code(200); // OK
      echo json_encode(array(
        "message" => "Results found",
        "results" => $results
      ));
    } else {
      http_response_code(404); // Not Found
      echo json_encode(['error' => 'No results found for the given filter']);
    }
  }

  public function create($data)
  {
    $result = $this->salesModel->create($data);
    if ($result) {
      http_response_code(200); // OK
      echo json_encode(array(
        "message" => "Data created successfully",
        "results" => $result
      ));
    } else {
      http_response_code(404); // Not Found
      echo json_encode(['error' => 'Data not created successfully']);
    }
  }

  public function update($data)
  {
    $result = $this->salesModel->update($data);

    if ($result) {
      if (isset($data['payforms'])) {
        foreach ($data['payforms'] as $payform) {
          $payform['id_sale'] = $data['id'];
          $salesPayFormsModel = new SalePayFormsModel();
          $salesPayFormsModel->create($payform);
        }
      }

      http_response_code(200); // OK
      echo json_encode(array(
        "message" => "Data updated successfully",
        "results" => $result
      ));
    } else {
      http_response_code(404); // Not Found
      echo json_encode(['error' => 'Data not updated successfully']);
    }
  }
}
