<?php

namespace App\Controllers;

use App\Models\CategorysModel;

class CategoryController
{
  protected $categoryModel;

  public function __construct($id = null)
  {
    $this->categoryModel = new CategorysModel($id ? $id : null);
  }

  public function find($data)
  {
    $categoryModel = new CategorysModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $categoryModel->find($filter, $limit);

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
    $result = $this->categoryModel->create($data);
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
    $result = $this->categoryModel->update($data);

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
