<?php

namespace App\Controllers;

use App\Models\EstadoModel;

class EstadoController
{
  protected $estadoModel;

  public function __construct($id = null)
  {
    $this->estadoModel = new EstadoModel($id ? $id : null);
  }

  public function find($data)
  {
    try {
      $estadoModel = new EstadoModel();
      $filter = $data && isset($data['filter']) ? $data['filter'] : null;
      $limit = $data && isset($data['limit']) ? $data['limit'] : null;
      $results = $estadoModel->find($filter, $limit);

      http_response_code(200);
      echo json_encode($results);
    } catch (\Exception $e) {
      http_response_code(403);
      echo json_encode(['message' => $e->getMessage()]);
    }
  }
}
