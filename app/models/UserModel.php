<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class UserModel extends Connection
{
  private $conn;
  private $id;
  private $name;
  private $email;
  private $password;
  private $photo;
  private $company;
  private $ativo;
  private $table = 'users';

  public function __construct($id = null)
  {
    $this->conn = $this->openConnection();

    if ($id) {
      $this->setId($id);
      $this->findById();
    }
  }

  private function findById()
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $user = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setName($user['name']);
      $this->setEmail($user['email']);
      $this->setPassword($user['password']);
      $this->setPhoto($user['photo']);
      $this->setCompany($user['company']);
      $this->setAtivo($user['ativo']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentUser()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->name = $this->getName();
    $data->email = $this->getEmail();
    $data->photo = $this->getPhoto();
    $data->company = $this->getCompany();
    $data->ativo = $this->getAtivo();
    $data->password = $this->getPassword();

    return $data;
  }

  public function find($filters = [], $limit = null)
  {
    $sql = "SELECT * FROM {$this->table}";

    if (!empty($filters)) {
      $sql .= " WHERE ";
      $sql .= implode(" AND ", array_map(function ($column) {
        return "$column = :$column";
      }, array_keys($filters)));
    }

    if ($limit !== null) {
      $sql .= " LIMIT :limit";
    }

    try {
      $stmt = $this->conn->prepare($sql);

      if (!empty($filters)) {
        foreach ($filters as $column => $value) {
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
    $sql = "INSERT INTO {$this->table} (name, email, password, photo, company, ativo) VALUES (:name, :email, :password, :photo, :company, :ativo)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':email', $data['email']);
      $stmt->bindParam(':password', $data['password']);
      $stmt->bindParam(':photo', $data['photo']);
      $stmt->bindParam(':company', $data['company']);
      $stmt->bindParam(':ativo', $data['ativo']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->findById();
      return $this->getCurrentUser();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET name = :name, email = :email, password = :password, photo = :photo, company = :company, ativo = :ativo WHERE id = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $data['id']);
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':email', $data['email']);
      $stmt->bindParam(':password', $data['password']);
      $stmt->bindParam(':photo', $data['photo']);
      $stmt->bindParam(':company', $data['company']);
      $stmt->bindParam(':ativo', $data['ativo']);
      $stmt->execute();

      $this->setId($data['id']);
      $this->findById();
      return $this->getCurrentUser();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function delete($id)
  {
    $sql = "DELETE FROM {$this->table} WHERE id = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Get the value of name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of email
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set the value of email
   *
   * @return  self
   */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get the value of password
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * Set the value of password
   *
   * @return  self
   */
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
   * Get the value of photo
   */
  public function getPhoto()
  {
    return $this->photo;
  }

  /**
   * Set the value of photo
   *
   * @return  self
   */
  public function setPhoto($photo)
  {
    $this->photo = $photo;

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
   * Get the value of ativo
   */
  public function getAtivo()
  {
    return $this->ativo;
  }

  /**
   * Set the value of ativo
   *
   * @return  self
   */
  public function setAtivo($ativo)
  {
    $this->ativo = $ativo;

    return $this;
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
}
