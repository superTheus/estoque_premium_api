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
  private $value_debit;
  private $value_credit;
  private $value_money;
  private $value_others;
  private $observacoes;
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
      $this->setValue_debit($caixa['value_debit']);
      $this->setValue_credit($caixa['value_credit']);
      $this->setValue_money($caixa['value_money']);
      $this->setValue_others($caixa['value_others']);
      $this->setObservacoes($caixa['observacoes']);
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
    $data->value_debit = $this->getValue_debit();
    $data->value_credit = $this->getValue_credit();
    $data->value_money = $this->getValue_money();
    $data->value_others = $this->getValue_others();
    $data->observacoes = $this->getObservacoes();
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

    $sql .= " ORDER BY id DESC";

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
    $sql = "INSERT INTO {$this->table} (id_company, id_user, value_init) 
            VALUES (:id_company, :id_user, :value_init)";

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
              value_debit = :value_debit,
              value_credit = :value_credit,
              value_money = :value_money,
              value_others = :value_others,
              observacoes = :observacoes,
              status = :status 
            WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':value_debit', $this->value_debit);
      $stmt->bindValue(':value_credit', $this->value_credit);
      $stmt->bindValue(':value_money', $this->value_money);
      $stmt->bindValue(':value_others', $this->value_others);
      $stmt->bindValue(':observacoes', $this->observacoes);
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
   * Get the value of value_debit
   */
  public function getValue_debit()
  {
    return $this->value_debit;
  }

  /**
   * Set the value of value_debit
   *
   * @return  self
   */
  public function setValue_debit($value_debit)
  {
    $this->value_debit = $value_debit;

    return $this;
  }

  /**
   * Get the value of value_credit
   */
  public function getValue_credit()
  {
    return $this->value_credit;
  }

  /**
   * Set the value of value_credit
   *
   * @return  self
   */
  public function setValue_credit($value_credit)
  {
    $this->value_credit = $value_credit;

    return $this;
  }

  /**
   * Get the value of value_money
   */
  public function getValue_money()
  {
    return $this->value_money;
  }

  /**
   * Set the value of value_money
   *
   * @return  self
   */
  public function setValue_money($value_money)
  {
    $this->value_money = $value_money;

    return $this;
  }

  /**
   * Get the value of value_others
   */
  public function getValue_others()
  {
    return $this->value_others;
  }

  /**
   * Set the value of value_others
   *
   * @return  self
   */
  public function setValue_others($value_others)
  {
    $this->value_others = $value_others;

    return $this;
  }

  /**
   * Get the value of observacoes
   */
  public function getObservacoes()
  {
    return $this->observacoes;
  }

  /**
   * Set the value of observacoes
   *
   * @return  self
   */
  public function setObservacoes($observacoes)
  {
    $this->observacoes = $observacoes;

    return $this;
  }
}
