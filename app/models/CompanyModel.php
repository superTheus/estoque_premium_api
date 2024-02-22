<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class CompanyModel extends Connection
{
  private $conn;
  private $id;
  private $cnpj;
  private $razao_social;
  private $nome_fantasia;
  private $telefone;
  private $email;
  private $cep;
  private $logradouro;
  private $numero;
  private $bairro;
  private $cidade;
  private $uf;
  private $datahora;
  private $table = 'company';

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

      $company = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setCnpj($company['cnpj']);
      $this->setRazao_social($company['razao_social']);
      $this->setNome_fantasia($company['nome_fantasia']);
      $this->setTelefone($company['telefone']);
      $this->setEmail($company['email']);
      $this->setCep($company['cep']);
      $this->setLogradouro($company['logradouro']);
      $this->setNumero($company['numero']);
      $this->setBairro($company['bairro']);
      $this->setCidade($company['cidade']);
      $this->setUf($company['uf']);
      $this->setDatahora($company['datahora']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentCompany()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->cnpj = $this->getCnpj();
    $data->razao_social = $this->getRazao_social();
    $data->nome_fantasia = $this->getNome_fantasia();
    $data->telefone = $this->getTelefone();
    $data->email = $this->getEmail();
    $data->cep = $this->getCep();
    $data->logradouro = $this->getLogradouro();
    $data->numero = $this->getNumero();
    $data->bairro = $this->getBairro();
    $data->cidade = $this->getCidade();
    $data->uf = $this->getUf();
    $data->datahora = $this->getDatahora();

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

      foreach ($filter as $column => $value) {
        $stmt->bindValue(":$column", $value);
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
    $sql = "INSERT INTO {$this->table} (name, address, phone) VALUES (:name, :address, :phone)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':address', $data['address']);
      $stmt->bindParam(':phone', $data['phone']);
      $stmt->execute();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET name = :name, address = :address, phone = :phone WHERE id = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $data['id']);
      $stmt->bindParam(':name', $data['name']);
      $stmt->bindParam(':address', $data['address']);
      $stmt->bindParam(':phone', $data['phone']);
      $stmt->execute();
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
   * Get the value of cnpj
   */
  public function getCnpj()
  {
    return $this->cnpj;
  }

  /**
   * Set the value of cnpj
   *
   * @return  self
   */
  public function setCnpj($cnpj)
  {
    $this->cnpj = $cnpj;

    return $this;
  }

  /**
   * Get the value of razao_social
   */
  public function getRazao_social()
  {
    return $this->razao_social;
  }

  /**
   * Set the value of razao_social
   *
   * @return  self
   */
  public function setRazao_social($razao_social)
  {
    $this->razao_social = $razao_social;

    return $this;
  }

  /**
   * Get the value of nome_fantasia
   */
  public function getNome_fantasia()
  {
    return $this->nome_fantasia;
  }

  /**
   * Set the value of nome_fantasia
   *
   * @return  self
   */
  public function setNome_fantasia($nome_fantasia)
  {
    $this->nome_fantasia = $nome_fantasia;

    return $this;
  }

  /**
   * Get the value of telefone
   */
  public function getTelefone()
  {
    return $this->telefone;
  }

  /**
   * Set the value of telefone
   *
   * @return  self
   */
  public function setTelefone($telefone)
  {
    $this->telefone = $telefone;

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
   * Get the value of cep
   */
  public function getCep()
  {
    return $this->cep;
  }

  /**
   * Set the value of cep
   *
   * @return  self
   */
  public function setCep($cep)
  {
    $this->cep = $cep;

    return $this;
  }

  /**
   * Get the value of logradouro
   */
  public function getLogradouro()
  {
    return $this->logradouro;
  }

  /**
   * Set the value of logradouro
   *
   * @return  self
   */
  public function setLogradouro($logradouro)
  {
    $this->logradouro = $logradouro;

    return $this;
  }

  /**
   * Get the value of numero
   */
  public function getNumero()
  {
    return $this->numero;
  }

  /**
   * Set the value of numero
   *
   * @return  self
   */
  public function setNumero($numero)
  {
    $this->numero = $numero;

    return $this;
  }

  /**
   * Get the value of bairro
   */
  public function getBairro()
  {
    return $this->bairro;
  }

  /**
   * Set the value of bairro
   *
   * @return  self
   */
  public function setBairro($bairro)
  {
    $this->bairro = $bairro;

    return $this;
  }

  /**
   * Get the value of cidade
   */
  public function getCidade()
  {
    return $this->cidade;
  }

  /**
   * Set the value of cidade
   *
   * @return  self
   */
  public function setCidade($cidade)
  {
    $this->cidade = $cidade;

    return $this;
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
   * Get the value of datahora
   */
  public function getDatahora()
  {
    return $this->datahora;
  }

  /**
   * Set the value of datahora
   *
   * @return  self
   */
  public function setDatahora($datahora)
  {
    $this->datahora = $datahora;

    return $this;
  }
}
