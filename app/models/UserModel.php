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
  private $profile;
  private $use_system;
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
      $this->setPassword($user['password']);
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
          if ("$column" === "password") {
            $value = md5($value);
          }

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
    $sql = "INSERT INTO {$this->table} (name, email, password, photo, company, profile, use_system) VALUES (:name, :email, :password, :photo, :company, :profile, :use_system)";

    try {
      $password_hash = md5($data['password']);
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':email', $data['email']);
      $stmt->bindParam(':password', $password_hash);
      $stmt->bindParam(':photo', $data['photo']);
      $stmt->bindParam(':company', $data['company']);
      $stmt->bindParam(':profile', $data['profile']);
      $stmt->bindParam(':use_system', $data['use_system']);
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
    $sql = "UPDATE {$this->table} SET name = :name, email = :email, password = :password, photo = :photo, company = :company, ativo = :ativo, profile = :profile, use_system = :use_system WHERE id = :id";

    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      if (isset($data['password']) && !empty($data['password'])) {
        $this->setPassword(md5($data['password']));
      }

      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $this->id);
      $stmt->bindParam(':name', $this->name);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':password', $this->password);
      $stmt->bindParam(':photo', $this->photo);
      $stmt->bindParam(':company', $this->company);
      $stmt->bindParam(':profile', $this->profile);
      $stmt->bindParam(':use_system', $this->use_system);
      $stmt->bindParam(':ativo', $this->ativo);
      $stmt->execute();

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

  /**
   * Get the value of profile
   */
  public function getProfile()
  {
    return $this->profile;
  }

  /**
   * Set the value of profile
   *
   * @return  self
   */
  public function setProfile($profile)
  {
    $this->profile = $profile;

    return $this;
  }

  /**
   * Get the value of use_system
   */
  public function getUse_system()
  {
    return $this->use_system;
  }

  /**
   * Set the value of use_system
   *
   * @return  self
   */
  public function setUse_system($use_system)
  {
    $this->use_system = $use_system;

    return $this;
  }
}
