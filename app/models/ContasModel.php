<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class ContasModel extends Connection
{
  private $conn;
  private $id;
  private $id_company;
  private $descricao;
  private $saldo_inicial;
  private $tipo_conta;
  private $cod_bancario;
  private $conta;
  private $agencia;
  private $deleted;
  private $table = 'contas';

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

      $conta = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setDescricao($conta['descricao']);
      $this->setId_company($conta['id_company']);
      $this->setSaldo_inicial($conta['saldo_inicial']);
      $this->setTipo_conta($conta['tipo_conta']);
      $this->setCod_bancario($conta['cod_bancario']);
      $this->setConta($conta['conta']);
      $this->setAgencia($conta['agencia']);
      $this->setDeleted($conta['deleted']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentBrand()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_company = $this->getId_company();
    $data->descricao = $this->getDescricao();
    $data->saldo_inicial = $this->getSaldo_inicial();
    $data->tipo_conta = $this->getTipo_conta();
    $data->cod_bancario = $this->getCod_bancario();
    $data->conta = $this->getConta();
    $data->agencia = $this->getAgencia();
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
    $sql = "INSERT INTO {$this->table} (id_company, descricao, saldo_inicial, tipo_conta, cod_bancario, conta, agencia) 
            VALUES (:id_company, :descricao, :saldo_inicial, :tipo_conta, :cod_bancario, :conta, :agencia)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':id_company', $data['id_company']);
      $stmt->bindValue(':descricao', $data['descricao']);
      $stmt->bindValue(':saldo_inicial', $data['saldo_inicial']);
      $stmt->bindValue(':tipo_conta', $data['tipo_conta']);
      $stmt->bindValue(':cod_bancario', $data['cod_bancario']);
      $stmt->bindValue(':conta', $data['conta']);
      $stmt->bindValue(':agencia', $data['agencia']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentBrand();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET 
      descricao = :descricao,
      saldo_inicial = :saldo_inicial,
      tipo_conta = :tipo_conta,
      cod_bancario = :cod_bancario,
      conta = :conta,
      agencia = :agencia,
      deleted = :deleted
    WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':descricao', $this->descricao);
      $stmt->bindValue(':saldo_inicial', $this->saldo_inicial);
      $stmt->bindValue(':tipo_conta', $this->tipo_conta);
      $stmt->bindValue(':cod_bancario', $this->cod_bancario);
      $stmt->bindValue(':conta', $this->conta);
      $stmt->bindValue(':agencia', $this->agencia);
      $stmt->bindParam(':deleted', $this->deleted);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentBrand();
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

  /**
   * Get the value of saldo_inicial
   */
  public function getSaldo_inicial()
  {
    return $this->saldo_inicial;
  }

  /**
   * Set the value of saldo_inicial
   *
   * @return  self
   */
  public function setSaldo_inicial($saldo_inicial)
  {
    $this->saldo_inicial = $saldo_inicial;

    return $this;
  }

  /**
   * Get the value of tipo_conta
   */
  public function getTipo_conta()
  {
    return $this->tipo_conta;
  }

  /**
   * Set the value of tipo_conta
   *
   * @return  self
   */
  public function setTipo_conta($tipo_conta)
  {
    $this->tipo_conta = $tipo_conta;

    return $this;
  }

  /**
   * Get the value of cod_bancario
   */
  public function getCod_bancario()
  {
    return $this->cod_bancario;
  }

  /**
   * Set the value of cod_bancario
   *
   * @return  self
   */
  public function setCod_bancario($cod_bancario)
  {
    $this->cod_bancario = $cod_bancario;

    return $this;
  }

  /**
   * Get the value of conta
   */
  public function getConta()
  {
    return $this->conta;
  }

  /**
   * Set the value of conta
   *
   * @return  self
   */
  public function setConta($conta)
  {
    $this->conta = $conta;

    return $this;
  }

  /**
   * Get the value of agencia
   */
  public function getAgencia()
  {
    return $this->agencia;
  }

  /**
   * Set the value of agencia
   *
   * @return  self
   */
  public function setAgencia($agencia)
  {
    $this->agencia = $agencia;

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
   * Get the value of descricao
   */
  public function getDescricao()
  {
    return $this->descricao;
  }

  /**
   * Set the value of descricao
   *
   * @return  self
   */
  public function setDescricao($descricao)
  {
    $this->descricao = $descricao;

    return $this;
  }
}
