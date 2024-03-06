<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class SalesProductsModel extends Connection
{
  private $conn;
  private $id;
  private $id_product;
  private $id_sale;
  private $quantity;
  private $desconto;
  private $desconto_percentual;
  private $total;
  private $table = 'sales_products';

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

      $sale = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setTotal($sale['total']);
      $this->setDesconto($sale['desconto']);
      $this->setDesconto_percentual($sale['desconto_percentual']);
      $this->setId_product($sale['id_product']);
      $this->setId_sale($sale['id_sale']);
      $this->setQuantity($sale['quantity']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentSale()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_product = $this->getId_product();
    $data->id_sale = $this->getId_sale();
    $data->quantity = $this->getQuantity();
    $data->desconto = $this->getDesconto();
    $data->desconto_percentual = $this->getDesconto_percentual();
    $data->total = $this->getTotal();
    return $data;
  }

  public function find($filter = [], $limit = null)
  {
    $sql = "SELECT * FROM {$this->table}";

    if (!empty($filter)) {
      $sql .= " WHERE ";
      $sql .= implode(" AND ", array_map(function ($column) {
        return "$column = :$column";
      }, array_keys($filter)));
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

      if ($limit !== null) {
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
      }

      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (id_product, id_sale, quantity, desconto, desconto_percentual, total) 
            VALUES (:id_product, :id_sale, :quantity, :desconto, :desconto_percentual, :total)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_product', $data['id_product']);
      $stmt->bindValue(':id_sale', $data['id_sale']);
      $stmt->bindValue(':quantity', $data['quantity']);
      $stmt->bindValue(':desconto', $data['desconto']);
      $stmt->bindValue(':desconto_percentual', $data['desconto_percentual']);
      $stmt->bindValue(':total', $data['total']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentSale();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET quantity = :quantity, desconto = :desconto, desconto_percentual = :desconto_percentual, total = :total WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);

      $stmt->bindValue(':quantity', $this->quantity);
      $stmt->bindValue(':desconto', $this->desconto);
      $stmt->bindValue(':desconto_percentual', $this->desconto_percentual);
      $stmt->bindValue(':total', $this->total);
      $stmt->bindValue(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentSale();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function delete()
  {
    $sql = "DELETE FROM {$this->table} WHERE id_product = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id', $this->getId_product());
      $stmt->execute();

      return $this->getCurrentSale();
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
   * Get the value of id_product
   */
  public function getId_product()
  {
    return $this->id_product;
  }

  /**
   * Set the value of id_product
   *
   * @return  self
   */
  public function setId_product($id_product)
  {
    $this->id_product = $id_product;

    return $this;
  }

  /**
   * Get the value of id_sale
   */
  public function getId_sale()
  {
    return $this->id_sale;
  }

  /**
   * Set the value of id_sale
   *
   * @return  self
   */
  public function setId_sale($id_sale)
  {
    $this->id_sale = $id_sale;

    return $this;
  }

  /**
   * Get the value of quantity
   */
  public function getQuantity()
  {
    return $this->quantity;
  }

  /**
   * Set the value of quantity
   *
   * @return  self
   */
  public function setQuantity($quantity)
  {
    $this->quantity = $quantity;

    return $this;
  }

  /**
   * Get the value of desconto
   */
  public function getDesconto()
  {
    return $this->desconto;
  }

  /**
   * Set the value of desconto
   *
   * @return  self
   */
  public function setDesconto($desconto)
  {
    $this->desconto = $desconto;

    return $this;
  }

  /**
   * Get the value of desconto_percentual
   */
  public function getDesconto_percentual()
  {
    return $this->desconto_percentual;
  }

  /**
   * Set the value of desconto_percentual
   *
   * @return  self
   */
  public function setDesconto_percentual($desconto_percentual)
  {
    $this->desconto_percentual = $desconto_percentual;

    return $this;
  }

  /**
   * Get the value of total
   */
  public function getTotal()
  {
    return $this->total;
  }

  /**
   * Set the value of total
   *
   * @return  self
   */
  public function setTotal($total)
  {
    $this->total = $total;

    return $this;
  }
}
