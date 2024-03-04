<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class ContasPagarReceberModel extends Connection
{
  private $conn;
  private $id;
  private $documento;
  private $tipo_conta;
  private $vencimento;
  private $descricao;
  private $valor;
  private $id_conta;
  private $id_company;
  private $status_pagamento;
  private $deleted;
  private $table = 'contas_pagar_receber';

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
      $this->setDocumento($conta['documento']);
      $this->setTipo_conta($conta['tipo_conta']);
      $this->setVencimento($conta['vencimento']);
      $this->setValor($conta['valor']);
      $this->setId_conta($conta['id_conta']);
      $this->setId_company($conta['id_company']);
      $this->setDeleted($conta['deleted']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentConta()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_company = $this->getId_company();
    $data->description = $this->getDescricao();
    $data->documento = $this->getDocumento();
    $data->tipo_conta = $this->getTipo_conta();
    $data->vencimento = $this->getVencimento();
    $data->valor = $this->getValor();
    $data->id_conta = $this->getId_conta();
    $data->status_pagamento = $this->getStatus_pagamento();
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
    $sql = "INSERT INTO {$this->table} (documento, tipo_conta, vencimento, descricao, valor, id_conta, id_company, status_pagamento) 
    VALUES (:documento, :tipo_conta, :vencimento, :descricao, :valor, :id_conta, :id_company, :status_pagamento)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':documento', $data['documento']);
      $stmt->bindValue(':tipo_conta', $data['tipo_conta']);
      $stmt->bindValue(':vencimento', $data['vencimento']);
      $stmt->bindValue(':descricao', $data['descricao']);
      $stmt->bindValue(':valor', $data['valor']);
      $stmt->bindValue(':id_conta', $data['id_conta']);
      $stmt->bindValue(':id_company', $data['id_company']);
      $stmt->bindValue(':status_pagamento', $data['status_pagamento']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentConta();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET 
              documento  = :documento,
              tipo_conta = :tipo_conta,
              vencimento = :vencimento,
              descricao = :descricao,
              valor = :valor,
              id_conta = :id_conta,
              status_pagamento = :status_pagamento,
              deleted = :deleted 
            WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':documento', $this->documento);
      $stmt->bindValue(':tipo_conta', $this->tipo_conta);
      $stmt->bindValue(':vencimento', $this->vencimento);
      $stmt->bindValue(':descricao', $this->descricao);
      $stmt->bindValue(':valor', $this->valor);
      $stmt->bindValue(':id_conta', $this->id_conta);
      $stmt->bindValue(':status_pagamento', $this->status_pagamento);
      $stmt->bindValue(':deleted', $this->deleted);
      $stmt->bindValue(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentConta();
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
   * Get the value of documento
   */
  public function getDocumento()
  {
    return $this->documento;
  }

  /**
   * Set the value of documento
   *
   * @return  self
   */
  public function setDocumento($documento)
  {
    $this->documento = $documento;

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
   * Get the value of vencimento
   */
  public function getVencimento()
  {
    return $this->vencimento;
  }

  /**
   * Set the value of vencimento
   *
   * @return  self
   */
  public function setVencimento($vencimento)
  {
    $this->vencimento = $vencimento;

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

  /**
   * Get the value of valor
   */
  public function getValor()
  {
    return $this->valor;
  }

  /**
   * Set the value of valor
   *
   * @return  self
   */
  public function setValor($valor)
  {
    $this->valor = $valor;

    return $this;
  }

  /**
   * Get the value of id_conta
   */
  public function getId_conta()
  {
    return $this->id_conta;
  }

  /**
   * Set the value of id_conta
   *
   * @return  self
   */
  public function setId_conta($id_conta)
  {
    $this->id_conta = $id_conta;

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
   * Get the value of status_pagamento
   */
  public function getStatus_pagamento()
  {
    return $this->status_pagamento;
  }

  /**
   * Set the value of status_pagamento
   *
   * @return  self
   */
  public function setStatus_pagamento($status_pagamento)
  {
    $this->status_pagamento = $status_pagamento;

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
}
