<?php

namespace App\Models;

use App\Models\Connection;
use PDO;

class PasswordLastsModel extends Connection
{
  private $conn;
  private $id;
  private $id_user;
  private $last_pass;
  private $table = 'password_lasts';


  public function __construct()
  {
    $this->conn = $this->openConnection();
  }

  public function find($id = null)
  {
    $query = "SELECT * FROM $this->table";

    if ($id) {
      $query .= " WHERE id_user = :id";
    }

    $stmt = $this->conn->prepare($query);

    if ($id) {
      $stmt->bindParam(':id', $id);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (id_user, last_pass) 
            VALUES (:id_user, :last_pass)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_user', $data['id_user']);
      $stmt->bindValue(':last_pass', $data['last_pass']);
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
   * Get the value of last_pass
   */
  public function getLast_pass()
  {
    return $this->last_pass;
  }

  /**
   * Set the value of last_pass
   *
   * @return  self
   */
  public function setLast_pass($last_pass)
  {
    $this->last_pass = $last_pass;

    return $this;
  }
}
