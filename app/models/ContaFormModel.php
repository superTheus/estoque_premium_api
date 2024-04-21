<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class ContaFormModel extends Connection
{
  private $conn;
  private $id;
  private $id_form;
  private $id_conta;
  private $date;
  private $table = 'conta_form';

  public function __construct()
  {
    $this->conn = $this->openConnection();
  }

  public function findByConta($id)
  {
    $sql = "SELECT * FROM {$this->table} WHERE id_conta = :id_conta";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":id_conta", $id);
      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (id_form, id_conta, date) 
            VALUES (:id_form, :id_conta, :date)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_form', isset($data['form']) ? $data['form'] : null);
      $stmt->bindValue(':id_conta', isset($data['conta']) ? $data['conta'] : null);
      $stmt->bindValue(':date', isset($data['date']) ? $data['date'] : null);
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
   * Get the value of id_form
   */
  public function getId_form()
  {
    return $this->id_form;
  }

  /**
   * Set the value of id_form
   *
   * @return  self
   */
  public function setId_form($id_form)
  {
    $this->id_form = $id_form;

    return $this;
  }

  /**
   * Get the value of id_conta
   */
  public function getId_conta()
  {
    return $this->id_conta;
  }

  /**
   * Set the value of id_conta
   *
   * @return  self
   */
  public function setId_conta($id_conta)
  {
    $this->id_conta = $id_conta;

    return $this;
  }

  /**
   * Get the value of date
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * Set the value of date
   *
   * @return  self
   */
  public function setDate($date)
  {
    $this->date = $date;

    return $this;
  }
}
