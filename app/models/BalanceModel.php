<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class BalanceModel extends Connection
{
  private $conn;
  private $id;
  private $id_product;
  private $id_company;
  private $balance_preview;
  private $balance_new;
  private $date_hour;
  private $type;
  private $table = 'products_mov';

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

      $this->setId_product($balance['id_product']);
      $this->setId_company($balance['id_company']);
      $this->setBalance_preview($balance['balance_preview']);
      $this->setBalance_new($balance['balance_new']);
      $this->setDate_hour($balance['date_hour']);
      $this->setType($balance['type']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentBalance()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_product = $this->getId_product();
    $data->id_company = $this->getId_company();
    $data->balance_preview = $this->getBalance_preview();
    $data->balance_new = $this->getBalance_new();
    $data->date_hour = $this->getDate_hour();
    $data->type = $this->getType();
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

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (id_product, id_company, balance_preview, balance_new, type) VALUES (:id_product, :id_company, :balance_preview, :balance_new, :type)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id_product', $data['id_product']);
      $stmt->bindParam(':id_company', $data['id_company']);
      $stmt->bindParam(':balance_preview', $data['balance_preview']);
      $stmt->bindParam(':balance_new', $data['balance_new']);
      $stmt->bindParam(':type', $data['type']);
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
   * Get the value of balance_preview
   */
  public function getBalance_preview()
  {
    return $this->balance_preview;
  }

  /**
   * Set the value of balance_preview
   *
   * @return  self
   */
  public function setBalance_preview($balance_preview)
  {
    $this->balance_preview = $balance_preview;

    return $this;
  }

  /**
   * Get the value of balance_new
   */
  public function getBalance_new()
  {
    return $this->balance_new;
  }

  /**
   * Set the value of balance_new
   *
   * @return  self
   */
  public function setBalance_new($balance_new)
  {
    $this->balance_new = $balance_new;

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
   * Get the value of type
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set the value of type
   *
   * @return  self
   */
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }
}
