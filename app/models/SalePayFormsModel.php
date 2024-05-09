<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class SalePayFormsModel extends Connection
{
  private $conn;
  private $id;
  private $id_form;
  private $id_sale;
  private $date;
  private $value;
  private $table = 'sales_payform';

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

      $this->setId_form($category['id_form']);
      $this->setId_sale($category['id_sale']);
      $this->setDate($category['date']);
      $this->setValue($category['value']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentSaleForm()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_form = $this->getId_form();
    $data->id_sale = $this->getId_sale();
    $data->date = $this->getDate();
    $data->value = $this->getValue();
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
    $sql = "INSERT INTO {$this->table} (id_form, id_sale, date, value) VALUES (:id_form, :id_sale, :date, :value)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id_form', $data['id_form']);
      $stmt->bindParam(':id_sale', $data['id_sale']);
      $stmt->bindParam(':date', $data['date']);
      $stmt->bindParam(':value', $data['value']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentSaleForm();
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
   * Get the value of id_sale
   */
  public function getId_sale()
  {
    return $this->id_sale;
  }

  /**
   * Set the value of id_sale
   *
   * @return  self
   */
  public function setId_sale($id_sale)
  {
    $this->id_sale = $id_sale;

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
}
