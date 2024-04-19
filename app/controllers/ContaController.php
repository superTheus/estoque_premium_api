<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\ContaModel;

class ContaController
{
  private $contaModel;

  public function __construct($id = null)
  {
    $this->contaModel = new ContaModel($id);
  }

  public function find($data)
  {
    $contaModel = new ContaModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $contaModel->find($filter, $limit);

    if ($results) {

      foreach ($results as $key => $value) {
        $client = new ClientsModel($value['client']);
        $results[$key]['clientData'] = $client->getCurrentClient();
      }

      http_response_code(200);
      echo json_encode(array(
        "message" => "Results found",
        "results" => $results
      ));
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'No results found for the given filter']);
    }
  }

  public function create($data)
  {
    $result = $this->contaModel->create($data);
    if ($result) {
      http_response_code(200);
      echo json_encode(array(
        "message" => "Data created successfully",
        "results" => $result
      ));
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Data not created successfully']);
    }
  }

  public function update($data)
  {
    $result = $this->contaModel->update($data);

    if ($result) {
      http_response_code(200);
      echo json_encode(array(
        "message" => "Data updated successfully",
        "results" => $result
      ));
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Data not updated successfully']);
    }
  }
}
