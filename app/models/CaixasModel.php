<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class CaixasModel extends Connection
{
  private $conn;
  private $id;
  private $id_company;
  private $id_user;
  private $value_init;
  private $value_final;
  private $status;
  private $table = 'caixas';

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

      $this->setId_company($caixa['id_company']);
      $this->setId_user($caixa['id_user']);
      $this->setValue_init($caixa['value_init']);
      $this->setValue_final($caixa['value_final']);
      $this->setStatus($caixa['status']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentBrand()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_company = $this->getId_company();
    $data->id_user = $this->getId_user();
    $data->value_init = $this->getValue_init();
    $data->value_final = $this->getValue_final();
    $data->status = $this->getStatus();
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
    $sql = "INSERT INTO {$this->table} (id_company, id_user, value_init) VALUES (:id_company, :id_user, :value_init)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_company', $data['id_company']);
      $stmt->bindValue(':id_user', $data['id_user']);
      $stmt->bindValue(':value_init', $data['value_init']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentBrand();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET 
              value_final = :value_final, status = :status 
            WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':value_final', $this->value_final);
      $stmt->bindValue(':status', $this->status);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentBrand();
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
   * Get the value of value_init
   */
  public function getValue_init()
  {
    return $this->value_init;
  }

  /**
   * Set the value of value_init
   *
   * @return  self
   */
  public function setValue_init($value_init)
  {
    $this->value_init = $value_init;

    return $this;
  }

  /**
   * Get the value of value_final
   */
  public function getValue_final()
  {
    return $this->value_final;
  }

  /**
   * Set the value of value_final
   *
   * @return  self
   */
  public function setValue_final($value_final)
  {
    $this->value_final = $value_final;

    return $this;
  }

  /**
   * Get the value of status
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * Set the value of status
   *
   * @return  self
   */
  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }
}
