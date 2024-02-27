<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class SubcategorysModel extends Connection
{
  private $conn;
  private $id;
  private $id_category;
  private $description;
  private $deleted;
  private $table = 'subcategorys';

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

      $category = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setDescription($category['description']);
      $this->setId_category($category['id_category']);
      $this->setDeleted($category['deleted']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentSubcategory()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->description = $this->getDescription();
    $data->id_category = $this->getId_category();
    $data->deleted = $this->getDeleted();
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

      return $stmt->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (id_category, description) VALUES (:id_category, :description)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id_category', $data['id_category']);
      $stmt->bindParam(':description', $data['description']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentSubcategory();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET id_category = :id_category, description = :description, deleted = :deleted WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id_category', $this->id_category);
      $stmt->bindParam(':description', $this->description);
      $stmt->bindParam(':deleted', $this->deleted);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentSubcategory();
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
   * Get the value of description
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set the value of description
   *
   * @return  self
   */
  public function setDescription($description)
  {
    $this->description = $description;

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
   * Get the value of id_category
   */
  public function getId_category()
  {
    return $this->id_category;
  }

  /**
   * Set the value of id_category
   *
   * @return  self
   */
  public function setId_category($id_category)
  {
    $this->id_category = $id_category;

    return $this;
  }
}
