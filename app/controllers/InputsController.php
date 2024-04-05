<?php

namespace App\Controllers;

use App\Models\InputsModel;
use App\Models\ProductsModel;
use App\Models\UserModel;

class InputsController
{
  protected $inputsModel;

  public function __construct($id = null)
  {
    $this->inputsModel = new InputsModel($id ? $id : null);
  }

  public function find($data)
  {
    $inputsModel = new InputsModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $order = $data && isset($data['order']) ? $data['order'] : null;
    $results = $inputsModel->find($filter, $limit, $order);

    if ($results) {
      foreach ($results as $key => $result) {
        $inputsModel->setId($result['id']);
        $results[$key]['products'] = $inputsModel->findProducts();

        foreach ($results[$key]['products'] as $k => $product) {
          $productModel = new ProductsModel($product['id_product']);
          $product['product'] = $productModel->getCurrentProduct();
          $results[$key]['products'][$k] = $product;
        }

        $userModel = new UserModel($result['id_user']);
        $results[$key]['user'] = $userModel->getCurrentUser();
      }

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
    $result = $this->inputsModel->create($data);
    if ($result) {
      foreach ($data['products'] as $product) {
        $this->inputsModel->setId_product($product['id_product']);
        $this->inputsModel->setQuantity($product['quantity']);
        $this->inputsModel->insertProduct();
      }

      http_response_code(200); // OK
      echo json_encode(array(
        "message" => "Data created successfully",
        "results" => $this->inputsModel->getCurrentInput()
      ));
    } else {
      http_response_code(404); // Not Found
      echo json_encode(['error' => 'Data not created successfully']);
    }
  }
}
