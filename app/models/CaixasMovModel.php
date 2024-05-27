<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class CaixasMovModel extends Connection
{
  private $conn;
  private $id;
  private $id_caixa;
  private $value;
  private $id_sale;
  private $date_hour;
  private $table = 'caixa_mov';

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

      $caixa = $stmt->fetch(\PDO::FETCH_ASSOC);
      $this->setId_caixa($caixa['id_caixa']);
      $this->setValue($caixa['value']);
      $this->setId_sale($caixa['id_sale']);
      $this->setDate_hour($caixa['date_hour']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentBrand()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_caixa = $this->getId_caixa();
    $data->value = $this->getValue();
    $data->id_sale = $this->getId_sale();
    $data->date_hour = $this->getDate_hour();
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
    $sql = "INSERT INTO {$this->table} (id_caixa, value, id_sale) 
            VALUES (:id_caixa, :value, :id_sale)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_caixa', $data['id_caixa']);
      $stmt->bindValue(':value', $data['value']);
      $stmt->bindValue(':id_sale', $data['id_sale']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentBrand();
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
   * Get the value of id_caixa
   */
  public function getId_caixa()
  {
    return $this->id_caixa;
  }

  /**
   * Set the value of id_caixa
   *
   * @return  self
   */
  public function setId_caixa($id_caixa)
  {
    $this->id_caixa = $id_caixa;

    return $this;
  }

  /**
   * Get the value of value
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * Set the value of value
   *
   * @return  self
   */
  public function setValue($value)
  {
    $this->value = $value;

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
}
