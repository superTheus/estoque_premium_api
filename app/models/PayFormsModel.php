<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class PayFormsModel extends Connection
{
  private $conn;
  private $id;
  private $description;
  private $table = 'payforms';

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

      $payform = $stmt->fetch(\PDO::FETCH_ASSOC);

      if ($payform) {
        $this->setDescription($payform['description']);
      }
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentSaleForm()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->description = $this->getDescription();
    return $data;
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
}
