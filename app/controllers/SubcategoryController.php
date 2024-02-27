<?php

namespace App\Controllers;

use App\Models\CategorysModel;
use App\Models\SubcategorysModel;
use App\Models\SubSubcategorysModel;

class SubcategoryController
{
  protected $subcategoryModel;

  public function __construct($id = null)
  {
    $this->subcategoryModel = new SubcategorysModel($id ? $id : null);
  }

  public function find($data)
  {
    $subcategoryModel = new SubcategorysModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $results = $subcategoryModel->find($filter, $limit);

    foreach ($results as $subcategory) {
      $categoryModel = new CategorysModel($subcategory->id_category);
      $subcategory->category = $categoryModel->getCurrentCategory();
    }

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
    $result = $this->subcategoryModel->create($data);

    if ($result) {
      $categoryModel = new CategorysModel($result->id_category);
      $result->category = $categoryModel->getCurrentCategory();
    }

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
    $result = $this->subcategoryModel->update($data);

    if ($result) {
      $categoryModel = new CategorysModel($result->id_category);
      $result->category = $categoryModel->getCurrentCategory();
    }

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
