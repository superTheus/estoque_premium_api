<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class InputsModel extends Connection
{
  private $conn;
  private $id;
  private $documento;
  private $date_hour;
  private $id_user;
  private $id_company;

  private $id_product;
  private $quantity;

  private $table = 'inputs';

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

      $balance = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setDocumento($balance['documento']);
      $this->setId_user($balance['id_user']);
      $this->setId_company($balance['id_company']);
      $this->setDate_hour($balance['date_hour']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentInput()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->documento = $this->getDocumento();
    $data->id_company = $this->getId_company();
    $data->getId_user = $this->getId_user();
    $data->date_hour = $this->getDate_hour();
    $data->products = $this->findProducts();
    return $data;
  }

  public function find($filter = [], $limit = null, $order = null)
  {
    $sql = "SELECT * FROM {$this->table}";

    if (!empty($filter)) {
      $sql .= " WHERE ";
      $sql .= implode(" AND ", array_map(function ($column) {
        return "$column = :$column";
      }, array_keys($filter)));
    }

    if ($order !== null) {
      $sql .= " ORDER BY " . $order['field'] . " " . $order['order'];
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

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function findProducts()
  {
    $sql = "SELECT * FROM input_products WHERE id_input = :id_input";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":id_input", $this->getId());
      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (documento, id_user, id_company) VALUES (:documento, :id_user, :id_company)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':documento', $data['documento']);
      $stmt->bindParam(':id_user', $data['id_user']);
      $stmt->bindParam(':id_company', $data['id_company']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentInput();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function insertProduct()
  {
    $sql = "INSERT INTO input_products (id_input, id_product, quantity) VALUES (:id_input, :id_product, :quantity)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_input', $this->getId());
      $stmt->bindValue(':id_product', $this->getId_product());
      $stmt->bindValue(':quantity', $this->getQuantity());
      $stmt->execute();

      return $this->conn->lastInsertId();
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
   * Get the value of documento
   */
  public function getDocumento()
  {
    return $this->documento;
  }

  /**
   * Set the value of documento
   *
   * @return  self
   */
  public function setDocumento($documento)
  {
    $this->documento = $documento;

    return $this;
  }

  /**
   * Get the value of date_hour
   */
  public function getDate_hour()
  {
    return $this->date_hour;
  }

  /**
   * Set the value of date_hour
   *
   * @return  self
   */
  public function setDate_hour($date_hour)
  {
    $this->date_hour = $date_hour;

    return $this;
  }

  /**
   * Get the value of id_user
   */
  public function getId_user()
  {
    return $this->id_user;
  }

  /**
   * Set the value of id_user
   *
   * @return  self
   */
  public function setId_user($id_user)
  {
    $this->id_user = $id_user;

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
}
