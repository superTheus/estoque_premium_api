<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class ContaModel extends Connection
{
  private $conn;
  private $id;
  private $value;
  private $payform;
  private $client;
  private $number_order;
  private $type;
  private $wild;
  private $portion_value;
  private $date_finance;
  private $date_expiration;
  private $observation;
  private $company;
  private $status;
  private $deleted;
  private $table = 'conta';

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

      $conta = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setValue($conta['value']);
      $this->setPayform($conta['payform']);
      $this->setClient($conta['client']);
      $this->setNumber_order($conta['number_order']);
      $this->setType($conta['type']);
      $this->setWild($conta['wild']);
      $this->setPortion_value($conta['portion_value']);
      $this->setDate_finance($conta['date_finance']);
      $this->setDate_expiration($conta['date_expiration']);
      $this->setObservation($conta['observation']);
      $this->setStatus($conta['status']);
      $this->setCompany($conta['company']);
      $this->setDeleted($conta['deleted']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentConta()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->value = $this->getValue();
    $data->payform = $this->getPayform();
    $data->client = $this->getClient();
    $data->number_order = $this->getNumber_order();
    $data->type = $this->getType();
    $data->wild = $this->getWild();
    $data->portion = $this->getPortion_value();
    $data->date_finance = $this->getDate_finance();
    $data->date_expiration = $this->getDate_expiration();
    $data->observation = $this->getObservation();
    $data->status = $this->getStatus();
    $data->company = $this->getCompany();
    $data->deleted = $this->getDeleted();

    return $data;
  }

  public function find($filter = [], $limit = null, $where = null)
  {
    $sql = "SELECT * FROM {$this->table}";

    if (!empty($filter)) {
      $sql .= " WHERE ";
      $sql .= implode(" AND ", array_map(function ($column) {
        return "$column = :$column";
      }, array_keys($filter)));
    }

    if ($where !== null) {
      if (!empty($filter)) {
        $sql .= " AND $where";
      } else {
        $sql .= " WHERE $where";
      }
    }

    $sql .= " ORDER BY date_expiration ASC";

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
    $sql = "INSERT INTO {$this->table} (value, payform, client, number_order, type, wild, portion_value, date_finance, date_expiration, observation, status, company) 
            VALUES (:value, :payform, :client, :number_order, :type, :wild, :portion_value, :date_finance, :date_expiration, :observation, :status, :company)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':value', isset($data['value']) ? $data['value'] : null);
      $stmt->bindValue(':payform', isset($data['payform']) ? $data['payform'] : null);
      $stmt->bindValue(':client', isset($data['client']) ? $data['client'] : null);
      $stmt->bindValue(':number_order', isset($data['number_order']) ? $data['number_order'] : null);
      $stmt->bindValue(':type', isset($data['type']) ? $data['type'] : null);
      $stmt->bindValue(':wild', isset($data['wild']) ? $data['wild'] : null);
      $stmt->bindValue(':portion_value', isset($data['portion_value']) ? $data['portion_value'] : null);
      $stmt->bindValue(':date_finance', isset($data['date_finance']) ? $data['date_finance'] : null);
      $stmt->bindValue(':date_expiration', isset($data['date_expiration']) ? $data['date_expiration'] : null);
      $stmt->bindValue(':observation', isset($data['observation']) ? $data['observation'] : null);
      $stmt->bindValue(':status', isset($data['status']) ? $data['status'] : null);
      $stmt->bindValue(':company', isset($data['company']) ? $data['company'] : null);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentConta();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET 
      value = :value,
      payform = :payform,
      client = :client,
      number_order = :number_order,
      type = :type,
      wild = :wild,
      portion_value = :portion_value,
      date_finance = :date_finance,
      date_expiration = :date_expiration,
      observation = :observation,
      status = :status,
      deleted = :delete
    WHERE id = :id";

    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':value', $this->value);
      $stmt->bindValue(':payform', $this->payform);
      $stmt->bindValue(':client', $this->client);
      $stmt->bindValue(':number_order', $this->number_order);
      $stmt->bindValue(':type', $this->type);
      $stmt->bindValue(':wild', $this->wild);
      $stmt->bindValue(':portion_value', $this->portion_value);
      $stmt->bindValue(':date_finance', $this->date_finance);
      $stmt->bindValue(':date_expiration', $this->date_expiration);
      $stmt->bindValue(':observation', $this->observation);
      $stmt->bindValue(':status', $this->status);
      $stmt->bindValue(':delete', $this->deleted);
      $stmt->bindValue(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentConta();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  // public function delete()
  // {
  //   $sql = "DELETE FROM {$this->table} WHERE id = :id";

  //   try {
  //     $stmt = $this->conn->prepare($sql);
  //     $stmt->bindParam(':id', $this->id);
  //     $stmt->execute();
  //   } catch (\PDOException $e) {
  //     echo $e->getMessage();
  //   }
  // }

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
   * Get the value of payform
   */
  public function getPayform()
  {
    return $this->payform;
  }

  /**
   * Set the value of payform
   *
   * @return  self
   */
  public function setPayform($payform)
  {
    $this->payform = $payform;

    return $this;
  }

  /**
   * Get the value of client
   */
  public function getClient()
  {
    return $this->client;
  }

  /**
   * Set the value of client
   *
   * @return  self
   */
  public function setClient($client)
  {
    $this->client = $client;

    return $this;
  }

  /**
   * Get the value of number_order
   */
  public function getNumber_order()
  {
    return $this->number_order;
  }

  /**
   * Set the value of number_order
   *
   * @return  self
   */
  public function setNumber_order($number_order)
  {
    $this->number_order = $number_order;

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

  /**
   * Get the value of wild
   */
  public function getWild()
  {
    return $this->wild;
  }

  /**
   * Set the value of wild
   *
   * @return  self
   */
  public function setWild($wild)
  {
    $this->wild = $wild;

    return $this;
  }

  /**
   * Get the value of date_finance
   */
  public function getDate_finance()
  {
    return $this->date_finance;
  }

  /**
   * Set the value of date_finance
   *
   * @return  self
   */
  public function setDate_finance($date_finance)
  {
    $this->date_finance = $date_finance;

    return $this;
  }

  /**
   * Get the value of date_expiration
   */
  public function getDate_expiration()
  {
    return $this->date_expiration;
  }

  /**
   * Set the value of date_expiration
   *
   * @return  self
   */
  public function setDate_expiration($date_expiration)
  {
    $this->date_expiration = $date_expiration;

    return $this;
  }

  /**
   * Get the value of observation
   */
  public function getObservation()
  {
    return $this->observation;
  }

  /**
   * Set the value of observation
   *
   * @return  self
   */
  public function setObservation($observation)
  {
    $this->observation = $observation;

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
   * Get the value of portion_value
   */
  public function getPortion_value()
  {
    return $this->portion_value;
  }

  /**
   * Set the value of portion_value
   *
   * @return  self
   */
  public function setPortion_value($portion_value)
  {
    $this->portion_value = $portion_value;

    return $this;
  }

  /**
   * Get the value of company
   */
  public function getCompany()
  {
    return $this->company;
  }

  /**
   * Set the value of company
   *
   * @return  self
   */
  public function setCompany($company)
  {
    $this->company = $company;

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
}
