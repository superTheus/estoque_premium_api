<?php

namespace App\Controllers;

use App\Models\MunicipioModel;

class MunicipioController
{
  protected $municipioModel;

  public function __construct($id = null)
  {
    $this->municipioModel = new MunicipioModel($id ? $id : null);
  }

  public function find($data)
  {
    try {
      $municipioModel = new MunicipioModel();
      $filter = $data && isset($data['filter']) ? $data['filter'] : null;
      $limit = $data && isset($data['limit']) ? $data['limit'] : null;
      $results = $municipioModel->find($filter, $limit);

      http_response_code(200);
      echo json_encode($results);
    } catch (\Exception $e) {
      http_response_code(403);
      echo json_encode(['message' => $e->getMessage()]);
    }
  }
}
