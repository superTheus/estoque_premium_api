<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class EstadoModel extends Connection
{
  private $conn;
  private $codigo;
  private $nome;
  private $uf;
  private $table = "estado";

  public function __construct($codigo = null)
  {
    $this->conn = $this->openConnection();
    if ($codigo) {
      $this->setCodigo($codigo);
      $this->findByCodigo();
    }
  }

  public function findByCodigo()
  {
    $sql = "SELECT * FROM {$this->table} WHERE codigo = :codigo";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':codigo', $this->getCodigo());
      $stmt->execute();
      $result = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setCodigo($result['codigo']);
      $this->setUf($result['uf']);
      $this->setNome($result['nome']);
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  public function current()
  {
    $data = new stdClass();
    $data->codigo = $this->getCodigo();
    $data->uf = $this->getUf();
    $data->nome = $this->getNome();

    return $data;
  }

  public function find($filters = [])
  {
    $sql = "SELECT * FROM {$this->table}";

    if (!empty($filters)) {
      $sql .= " WHERE ";
      $sql .= implode(" AND ", array_map(function ($key, $value) {
        if (is_string($value)) {
          $value = "'$value'";
        }
        return "$key = $value";
      }, array_keys($filters), $filters));
    }

    $sql .= " ORDER BY nome ASC";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Get the value of uf
   */
  public function getUf()
  {
    return $this->uf;
  }

  /**
   * Set the value of uf
   *
   * @return  self
   */
  public function setUf($uf)
  {
    $this->uf = $uf;

    return $this;
  }

  /**
   * Get the value of codigo
   */
  public function getCodigo()
  {
    return $this->codigo;
  }

  /**
   * Set the value of codigo
   *
   * @return  self
   */
  public function setCodigo($codigo)
  {
    $this->codigo = $codigo;

    return $this;
  }

  /**
   * Get the value of nome
   */
  public function getNome()
  {
    return $this->nome;
  }

  /**
   * Set the value of nome
   *
   * @return  self
   */
  public function setNome($nome)
  {
    $this->nome = $nome;

    return $this;
  }
}
