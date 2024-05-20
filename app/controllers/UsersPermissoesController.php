<?php

namespace App\Controllers;

use App\Models\PermissaoUsuarioModel;

class UsersPermissoesController
{
  protected $permissoesUsuarioModel;

  public function __construct($id = null)
  {
    $this->permissoesUsuarioModel = new PermissaoUsuarioModel($id ? $id : null);
  }

  public function update($data)
  {
    $result = $this->permissoesUsuarioModel->update($data);

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
