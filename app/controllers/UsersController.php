<?php

namespace App\Controllers;

use App\Models\UserModel;

class UsersController
{
  protected $userModel;

  public function __construct($data = null)
  {
    $this->userModel = new UserModel($data ? $data["id"] : null);
  }

  public function find($data)
  {
    $userModel = new UserModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $userModel->find($filter, $limit);

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
    return $this->userModel->create($data);
  }

  public function update($data)
  {
    return $this->userModel->update($data);
  }
}
