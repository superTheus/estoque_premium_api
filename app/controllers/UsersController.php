<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\UserModel;
use App\Models\PasswordLastsModel;
use App\Models\PermissaoUsuarioModel;

class UsersController
{
  protected $userModel;

  public function __construct($id = null)
  {
    $this->userModel = new UserModel($id ? $id : null);
  }

  public function find($data)
  {
    $userModel = new UserModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $userModel->find($filter, $limit);

    if ($results) {

      foreach ($results as $key => $user) {
        $companyModel = new CompanyModel($user['company']);
        $results[$key]['companyData'] = $companyModel->getCurrentCompany();

        $passwordLastsModel = new PasswordLastsModel();
        $last = $passwordLastsModel->find($user['id']);
        $results[$key]['lastpassword'] = $last ? $last[0] : null;

        $permissaoUsuario = new PermissaoUsuarioModel();
        $permissao = $permissaoUsuario->find([
          "user" => $user['id']
        ]);

        $results[$key]['permissao'] = $permissao ? $permissao[0] : null;
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
      $permissaoUsuario = new PermissaoUsuarioModel();

      $permissaoUsuario->create([
        'user' => $result->id,
      ]);

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

  public function setLastPass($data)
  {
    $passwordLastsModel = new PasswordLastsModel();
    $result = $passwordLastsModel->create($data);

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
}
