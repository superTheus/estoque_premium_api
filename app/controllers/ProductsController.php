<?php

namespace App\Controllers;

use App\Models\BrandsModel;
use App\Models\CategorysModel;
use App\Models\ImagesProductsModel;
use App\Models\ProductsModel;
use App\Models\SubcategorysModel;
use App\Models\SupplierModel;

class ProductsController
{
  protected $productsModel;

  public function __construct($id = null)
  {
    $this->productsModel = new ProductsModel($id ? $id : null);
  }

  public function find($data)
  {
    $productsModel = new ProductsModel();
    $filter = $data && isset($data['filter']) ? $data['filter'] : null;
    $limit = $data && isset($data['limit']) ? $data['limit'] : null;
    $search = $data && isset($data['search']) ? $data['search'] : null;
    $results = $productsModel->find($filter, $limit, $search);

    foreach ($results as $key => $value) {
      $brand = new BrandsModel($value['id_brand']);
      $results[$key]['brand'] = $brand->getCurrentBrand();

      $category = new CategorysModel($value['id_category']);
      $results[$key]['category'] = $category->getCurrentCategory();

      $subcategory = new SubcategorysModel($value['id_subcategory']);
      $results[$key]['subcategory'] = $subcategory->getCurrentSubcategory();

      $supplier = new SupplierModel($value['id_fornecedor']);
      $results[$key]['supplier'] = $supplier->getCurrentSupplier();

      $imagesProductsModel = new ImagesProductsModel();
      $images = $imagesProductsModel->find(['id_product' => $value['id']]);
      $results[$key]['images'] = $images;
    }

    http_response_code(200); // OK
    echo json_encode(array(
      "message" => "Results found",
      "results" => $results
    ));
  }

  public function create($data)
  {
    $result = $this->productsModel->create($data);
    if ($result) {

      if ($data['images']) {
        foreach ($data['images'] as $key => $value) {
          $imagesProductsModel = new ImagesProductsModel();

          $dataImages = [
            'id_product' => $result->id,
            'image' => $value
          ];

          $imagesProductsModel->create($dataImages);
        }
      }

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
    $result = $this->productsModel->update($data);

    if ($result) {
      $imagesProductsModel = new ImagesProductsModel();
      $images = $imagesProductsModel->find(['id_product' => $result->id]);

      if ($images) {
        foreach ($images as $key => $value) {
          $imagesProductsModel->delete($value->id);
        }
      }

      if ($data['images']) {
        foreach ($data['images'] as $key => $value) {
          $imagesProductsModel = new ImagesProductsModel();

          $dataImages = [
            'id_product' => $result->id,
            'image' => $value
          ];

          $imagesProductsModel->create($dataImages);
        }
      }
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
