<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class ProductsModel extends Connection
{
  private $conn;
  private $id;
  private $description;
  private $id_company;
  private $id_brand;
  private $id_category;
  private $id_subcategory;
  private $price_sale;
  private $price_cost;
  private $ncm;
  private $id_fornecedor;
  private $control_stock;
  private $stock;
  private $stock_minimum;
  private $deleted;
  private $table = 'prodcuts';

  public function __construct($id = null)
  {
    $this->conn = $this->openConnection();

    if ($id) {
      $this->setId($id);
      $this->getById();
    }
  }

  private function getById()
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $product = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setDescription($product['description']);
      $this->setId_company($product['id_company']);
      $this->setId_brand($product['id_brand']);
      $this->setId_category($product['id_category']);
      $this->setId_subcategory($product['id_subcategory']);
      $this->setPrice_sale($product['price_sale']);
      $this->setPrice_cost($product['price_cost']);
      $this->setNcm($product['ncm']);
      $this->setId_fornecedor($product['id_fornecedor']);
      $this->setControl_stock($product['control_stock']);
      $this->setStock($product['stock']);
      $this->setDeleted($product['deleted']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentProduct()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_company = $this->getId_company();
    $data->description = $this->getDescription();
    $data->id_brand = $this->getId_brand();
    $data->id_category = $this->getId_category();
    $data->id_subcategory = $this->getId_subcategory();
    $data->price_sale = $this->getPrice_sale();
    $data->price_cost = $this->getPrice_cost();
    $data->ncm = $this->getNcm();
    $data->id_fornecedor = $this->getId_fornecedor();
    $data->control_stock = $this->getControl_stock();
    $data->stock = $this->getStock();
    $data->deleted = $this->getDeleted();
    return $data;
  }

  public function find($filter = [], $limit = null, $search = null)
  {
    $sql = "SELECT * FROM {$this->table}";

    $whereClauses = [];
    if (!empty($filter)) {
      $whereClauses[] = implode(" AND ", array_map(function ($column) {
        return "$column = :$column";
      }, array_keys($filter)));
    }

    if ($search !== null) {
      $whereClauses[] = "description LIKE :search";
    }

    if (!empty($whereClauses)) {
      $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    if ($limit !== null) {
      $sql .= " LIMIT :limit";
    }

    try {
      $stmt = $this->conn->prepare($sql);

      if (!empty($filter)) {
        foreach ($filter as $column => $value) {
          $stmt->bindValue(":$column", $value);
        }
      }

      if ($search !== null) {
        $stmt->bindValue(':search', '%' . $search . '%');
      }

      if ($limit !== null) {
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
      }

      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} 
          (description, id_company, id_brand, id_category, id_subcategory, price_sale, price_cost, ncm, id_fornecedor, control_stock, stock, stock_minimum) 
           VALUES (:description, :id_company, :id_brand, :id_category, :id_subcategory, :price_sale, :price_cost, :ncm, :id_fornecedor, :control_stock, :stock, :stock_minimum)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':description', $data['description']);
      $stmt->bindValue(':id_company', $data['id_company']);
      $stmt->bindValue(':id_brand', $data['id_brand']);
      $stmt->bindValue(':id_category', $data['id_category']);
      $stmt->bindValue(':id_subcategory', $data['id_subcategory']);
      $stmt->bindValue(':price_sale', $data['price_sale']);
      $stmt->bindValue(':price_cost', $data['price_cost']);
      $stmt->bindValue(':ncm', $data['ncm']);
      $stmt->bindValue(':id_fornecedor', $data['id_fornecedor']);
      $stmt->bindValue(':control_stock', $data['control_stock']);
      $stmt->bindValue(':stock', $data['stock']);
      $stmt->bindValue(':stock_minimum', $data['stock_minimum']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentProduct();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET 
              description = :description, 
              id_brand = :id_brand, 
              id_category = :id_category, 
              id_subcategory = :id_subcategory, 
              price_sale = :price_sale, 
              price_cost = :price_cost,
              ncm = :ncm, 
              id_fornecedor = :id_fornecedor, 
              control_stock = :control_stock, 
              stock = :stock,
              stock_minimum = :stock_minimum,
              deleted = :deleted
            WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':description', $data['description']);
      $stmt->bindValue(':id_brand', $this->id_brand);
      $stmt->bindValue(':id_category', $this->id_category);
      $stmt->bindValue(':id_subcategory', $this->id_subcategory);
      $stmt->bindValue(':price_sale', $this->price_sale);
      $stmt->bindValue(':price_cost', $this->price_cost);
      $stmt->bindValue(':ncm', $this->ncm);
      $stmt->bindValue(':id_fornecedor', $this->id_fornecedor);
      $stmt->bindValue(':control_stock', $this->control_stock);
      $stmt->bindValue(':stock', $this->stock);
      $stmt->bindValue(':stock_minimum', $this->stock_minimum);
      $stmt->bindValue(':deleted', $this->deleted);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentProduct();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function delete()
  {
    $sql = "DELETE FROM {$this->table} WHERE id = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Get the value of id
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of description
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set the value of description
   *
   * @return  self
   */
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get the value of id_company
   */
  public function getId_company()
  {
    return $this->id_company;
  }

  /**
   * Set the value of id_company
   *
   * @return  self
   */
  public function setId_company($id_company)
  {
    $this->id_company = $id_company;

    return $this;
  }

  /**
   * Get the value of id_brand
   */
  public function getId_brand()
  {
    return $this->id_brand;
  }

  /**
   * Set the value of id_brand
   *
   * @return  self
   */
  public function setId_brand($id_brand)
  {
    $this->id_brand = $id_brand;

    return $this;
  }

  /**
   * Get the value of id_category
   */
  public function getId_category()
  {
    return $this->id_category;
  }

  /**
   * Set the value of id_category
   *
   * @return  self
   */
  public function setId_category($id_category)
  {
    $this->id_category = $id_category;

    return $this;
  }

  /**
   * Get the value of id_subcategory
   */
  public function getId_subcategory()
  {
    return $this->id_subcategory;
  }

  /**
   * Set the value of id_subcategory
   *
   * @return  self
   */
  public function setId_subcategory($id_subcategory)
  {
    $this->id_subcategory = $id_subcategory;

    return $this;
  }

  /**
   * Get the value of price_sale
   */
  public function getPrice_sale()
  {
    return $this->price_sale;
  }

  /**
   * Set the value of price_sale
   *
   * @return  self
   */
  public function setPrice_sale($price_sale)
  {
    $this->price_sale = $price_sale;

    return $this;
  }

  /**
   * Get the value of price_cost
   */
  public function getPrice_cost()
  {
    return $this->price_cost;
  }

  /**
   * Set the value of price_cost
   *
   * @return  self
   */
  public function setPrice_cost($price_cost)
  {
    $this->price_cost = $price_cost;

    return $this;
  }

  /**
   * Get the value of ncm
   */
  public function getNcm()
  {
    return $this->ncm;
  }

  /**
   * Set the value of ncm
   *
   * @return  self
   */
  public function setNcm($ncm)
  {
    $this->ncm = $ncm;

    return $this;
  }

  /**
   * Get the value of id_fornecedor
   */
  public function getId_fornecedor()
  {
    return $this->id_fornecedor;
  }

  /**
   * Set the value of id_fornecedor
   *
   * @return  self
   */
  public function setId_fornecedor($id_fornecedor)
  {
    $this->id_fornecedor = $id_fornecedor;

    return $this;
  }

  /**
   * Get the value of control_stock
   */
  public function getControl_stock()
  {
    return $this->control_stock;
  }

  /**
   * Set the value of control_stock
   *
   * @return  self
   */
  public function setControl_stock($control_stock)
  {
    $this->control_stock = $control_stock;

    return $this;
  }

  /**
   * Get the value of stock
   */
  public function getStock()
  {
    return $this->stock;
  }

  /**
   * Set the value of stock
   *
   * @return  self
   */
  public function setStock($stock)
  {
    $this->stock = $stock;

    return $this;
  }

  /**
   * Get the value of deleted
   */
  public function getDeleted()
  {
    return $this->deleted;
  }

  /**
   * Set the value of deleted
   *
   * @return  self
   */
  public function setDeleted($deleted)
  {
    $this->deleted = $deleted;

    return $this;
  }

  /**
   * Get the value of stock_minimum
   */
  public function getStock_minimum()
  {
    return $this->stock_minimum;
  }

  /**
   * Set the value of stock_minimum
   *
   * @return  self
   */
  public function setStock_minimum($stock_minimum)
  {
    $this->stock_minimum = $stock_minimum;

    return $this;
  }
}
