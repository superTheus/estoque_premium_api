<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class SupplierModel extends Connection
{
  private $conn;
  private $id;
  private $name;
  private $document;
  private $id_company;
  private $deleted;
  private $table = 'supplier';

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

      $this->setName($category['name']);
      $this->setDocument($category['document']);
      $this->setDeleted($category['deleted']);
      $this->setId_company($category['id_company']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentSupplier()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->name = $this->getName();
    $data->document = $this->getDocument();
    $data->id_company = $this->getId_company();
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

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function create($data)
  {
    $sql = "INSERT INTO {$this->table} (name, document, id_company) VALUES (:name, :document, :id_company)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':document', $data['document']);
      $stmt->bindParam(':id_company', $data['id_company']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentSupplier();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET name = :name, document = :document, id_company = :id_company, deleted = :deleted WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $this->name);
      $stmt->bindParam(':document', $this->document);
      $stmt->bindParam(':id_company', $this->id_company);
      $stmt->bindParam(':deleted', $this->deleted);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentSupplier();
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
   * Get the value of document
   */
  public function getDocument()
  {
    return $this->document;
  }

  /**
   * Set the value of document
   *
   * @return  self
   */
  public function setDocument($document)
  {
    $this->document = $document;

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
}
