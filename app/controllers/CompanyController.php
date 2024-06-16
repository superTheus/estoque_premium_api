<?php

namespace App\Controllers;

use App\Models\ClientsModel;
use App\Models\CompanyModel;
use App\Models\PasswordLastsModel;
use App\Models\UserModel;

class CompanyController
{
  protected $userModel;

  public function __construct($id = null)
  {
    $this->userModel = new CompanyModel($id ? $id : null);
  }

  public function find($data)
  {
    $userModel = new CompanyModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $userModel->find($filter, $limit);

    if ($results) {

      foreach ($results as $key => $value) {
        $results[$key]['users'] = (new UserModel())->find(['company' => $value['id']]);

        foreach ($results[$key]['users'] as $k => $user) {
          $passwordLastsModel = new PasswordLastsModel();
          $last = $passwordLastsModel->find($user['id']);
          $results[$key]['users'][$k]['lastpassword'] = $last ? $last[0] : null;
        }
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
    $result = $this->userModel->create($data);

    if ($result) {
      $data = [
        "name" => "Consumidor Final",
        "id_company" => $result->id,
        "show_client" => 'N',
        "order" => 0
      ];

      $clientModel = new ClientsModel();
      $clientModel->create($data);

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
    $result = $this->userModel->update($data);

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
