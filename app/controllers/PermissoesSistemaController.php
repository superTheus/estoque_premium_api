<?php

namespace App\Controllers;

use App\Models\PermissoesSistemaModel;

class PermissoesSistemaController
{
  protected $permissaoSistemaModel;

  public function __construct($id = null)
  {
    $this->permissaoSistemaModel = new PermissoesSistemaModel($id ? $id : null);
  }

  public function find($data)
  {
    $permissaoSistemaModel = new PermissoesSistemaModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $permissaoSistemaModel->find($filter, $limit);

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
    $result = $this->permissaoSistemaModel->create($data);
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
    $result = $this->permissaoSistemaModel->update($data);

    if ($result) {
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
