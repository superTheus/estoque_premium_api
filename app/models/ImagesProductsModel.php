<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class ImagesProductsModel extends Connection
{
  private $conn;
  private $id;
  private $id_product;
  private $image;
  private $table = 'products_images';

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

      if ($category) {
        $this->setId_product($category['id_product']);
        $this->setImage($category['image']);
      }
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentImage()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_product = $this->getId_product();
    $data->image = $this->getImage();
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
    $sql = "INSERT INTO {$this->table} (id_product, image) 
            VALUES (:id_product, :image)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id_product', $data['id_product']);
      $stmt->bindParam(':image', $data['image']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentImage();
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
   * Get the value of id_product
   */
  public function getId_product()
  {
    return $this->id_product;
  }

  /**
   * Set the value of id_product
   *
   * @return  self
   */
  public function setId_product($id_product)
  {
    $this->id_product = $id_product;

    return $this;
  }

  /**
   * Get the value of image
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * Set the value of image
   *
   * @return  self
   */
  public function setImage($image)
  {
    $this->image = $image;

    return $this;
  }
}
