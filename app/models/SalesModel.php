<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class SalesModel extends Connection
{
  private $conn;
  private $id;
  private $id_company;
  private $id_user;
  private $id_client;
  private $total;
  private $deleted;
  private $status;
  private $date_hour;
  private $table = 'sales';

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


      $this->setId_company($sale['id_company']);
      $this->setId_user($sale['id_user']);
      $this->setId_client($sale['id_client']);
      $this->setTotal($sale['total']);
      $this->setDeleted($sale['deleted']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentSale()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_company = $this->getId_company();
    $data->id_user = $this->getId_user();
    $data->id_client = $this->getId_client();
    $data->total = $this->getTotal();
    $data->status = $this->getStatus();
    $data->date_hour = $this->getDate_hour();
    $data->deleted = $this->getDeleted();
    return $data;
  }

  public function find($filter = [], $limit = null, $condition = null)
  {
    $sql = "SELECT * FROM {$this->table} WHERE 1 = 1 ";

    if (isset($filter['date_init']) && isset($filter['date_end'])) {
      $dateInit = $filter['date_init'];
      $dateEnd = $filter['date_end'];
      $sql .= " AND DATE(date_hour) BETWEEN DATE('$dateInit') AND DATE('$dateEnd')";

      unset($filter['date_init']);
      unset($filter['date_end']);
    }

    if (!empty($filter)) {
      $sql .= " AND ";
      $sql .= implode(" AND ", array_map(function ($column) {
        return "$column = :$column";
      }, array_keys($filter)));
    }

    $sql .= " ORDER BY date_hour DESC";

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
    $sql = "INSERT INTO {$this->table} (id_company, total) VALUES (:id_company, :total)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_company', $data['id_company']);
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
    $sql = "UPDATE {$this->table} SET total = :total, status = :status, id_client = :id_client, id_user = :id_user, deleted = :deleted WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':total', $this->total);
      $stmt->bindParam(':status', $this->status);
      $stmt->bindParam(':id_client', $this->id_client);
      $stmt->bindParam(':id_user', $this->id_user);
      $stmt->bindParam(':deleted', $this->deleted);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentSale();
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
   * Get the value of id_client
   */
  public function getId_client()
  {
    return $this->id_client;
  }

  /**
   * Set the value of id_client
   *
   * @return  self
   */
  public function setId_client($id_client)
  {
    $this->id_client = $id_client;

    return $this;
  }
}
