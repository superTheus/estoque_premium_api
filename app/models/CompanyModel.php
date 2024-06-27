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
  private $inscricao_estadual;
  private $inscricao_municipal;
  private $email;
  private $telefone;
  private $cep;
  private $logradouro;
  private $numero;
  private $bairro;
  private $cidade;
  private $uf;
  private $certificate;
  private $cetificate_name;
  private $password;
  private $csc;
  private $csc_id;
  private $ativo;
  private $type;
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
      $this->setCertificate($company['certificate']);
      $this->setPassword($company['password']);
      $this->setCsc($company['csc']);
      $this->setCsc_id($company['csc_id']);
      $this->setType($company['type']);
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
    $data->inscricao_estadual = $this->getInscricao_estadual();
    $data->inscricao_municipal = $this->getInscricao_municipal();
    $data->telefone = $this->getTelefone();
    $data->email = $this->getEmail();
    $data->cep = $this->getCep();
    $data->logradouro = $this->getLogradouro();
    $data->numero = $this->getNumero();
    $data->bairro = $this->getBairro();
    $data->cidade = $this->getCidade();
    $data->uf = $this->getUf();
    $data->datahora = $this->getDatahora();
    $data->certificate = $this->getCertificate();
    $data->cetificate_name = $this->getCetificate_name();
    $data->password = $this->getPassword();
    $data->csc = $this->getCsc();
    $data->csc_id = $this->getCsc_id();
    $data->type = $this->getType();
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
    $sql = "INSERT INTO {$this->table} (cnpj, razao_social, nome_fantasia, inscricao_estadual, inscricao_municipal, telefone, email, cep, logradouro, numero, bairro, cidade, uf) 
    VALUES (:cnpj, :razao_social, :nome_fantasia, :inscricao_estadual, :inscricao_municipal, :telefone, :email, :cep, :logradouro, :numero, :bairro, :cidade, :uf)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':cnpj', $data['cnpj']);
      $stmt->bindParam(':razao_social', $data['razao_social']);
      $stmt->bindParam(':nome_fantasia', $data['nome_fantasia']);
      $stmt->bindParam(':inscricao_estadual', $data['inscricao_estadual']);
      $stmt->bindParam(':inscricao_municipal', $data['inscricao_municipal']);
      $stmt->bindParam(':email', $data['email']);
      $stmt->bindParam(':telefone', $data['telefone']);
      $stmt->bindParam(':cep', $data['cep']);
      $stmt->bindParam(':logradouro', $data['logradouro']);
      $stmt->bindParam(':numero', $data['numero']);
      $stmt->bindParam(':bairro', $data['bairro']);
      $stmt->bindParam(':cidade', $data['cidade']);
      $stmt->bindParam(':uf', $data['uf']);
      $stmt->bindParam(':certificate', $data['certificate']);
      $stmt->bindParam(':password', $data['password']);
      $stmt->bindParam(':csc', $data['csc']);
      $stmt->bindParam(':csc_id', $data['csc_id']);

      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentCompany();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} 
            SET cnpj = :cnpj, razao_social = :razao_social, nome_fantasia = :nome_fantasia, 
              inscricao_estadual = :inscricao_estadual, inscricao_municipal = :inscricao_municipal, 
              telefone = :telefone, email = :email, cep = :cep, 
              logradouro = :logradouro, numero = :numero, bairro = :bairro, cidade = :cidade, 
              uf = :uf, datahora = :datahora, certificate = :certificate, cetificate_name = :cetificate_name, password = :password, 
              csc = :csc, csc_id = :csc_id, ativo  = :ativo
            WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $this->id);
      $stmt->bindParam(':cnpj', $this->cnpj);
      $stmt->bindParam(':razao_social', $this->razao_social);
      $stmt->bindParam(':nome_fantasia', $this->nome_fantasia);
      $stmt->bindParam(':inscricao_estadual', $this->inscricao_estadual);
      $stmt->bindParam(':inscricao_municipal', $this->inscricao_municipal);
      $stmt->bindParam(':telefone', $this->telefone);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':cep', $this->cep);
      $stmt->bindParam(':logradouro', $this->logradouro);
      $stmt->bindParam(':numero', $this->numero);
      $stmt->bindParam(':bairro', $this->bairro);
      $stmt->bindParam(':cidade', $this->cidade);
      $stmt->bindParam(':uf', $this->uf);
      $stmt->bindParam(':datahora', $this->datahora);
      $stmt->bindParam(':certificate', $this->certificate);
      $stmt->bindParam(':cetificate_name', $this->cetificate_name);
      $stmt->bindParam(':password', $this->password);
      $stmt->bindParam(':csc', $this->csc);
      $stmt->bindParam(':csc_id', $this->csc_id);
      $stmt->bindParam(':ativo', $this->ativo);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentCompany();
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

  /**
   * Get the value of certificate
   */
  public function getCertificate()
  {
    return $this->certificate;
  }

  /**
   * Set the value of certificate
   *
   * @return  self
   */
  public function setCertificate($certificate)
  {
    $this->certificate = $certificate;

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
   * Get the value of csc
   */
  public function getCsc()
  {
    return $this->csc;
  }

  /**
   * Set the value of csc
   *
   * @return  self
   */
  public function setCsc($csc)
  {
    $this->csc = $csc;

    return $this;
  }

  /**
   * Get the value of csc_id
   */
  public function getCsc_id()
  {
    return $this->csc_id;
  }

  /**
   * Set the value of csc_id
   *
   * @return  self
   */
  public function setCsc_id($csc_id)
  {
    $this->csc_id = $csc_id;

    return $this;
  }

  /**
   * Get the value of type
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set the value of type
   *
   * @return  self
   */
  public function setType($type)
  {
    $this->type = $type;

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
   * Get the value of inscricao_estadual
   */
  public function getInscricao_estadual()
  {
    return $this->inscricao_estadual;
  }

  /**
   * Set the value of inscricao_estadual
   *
   * @return  self
   */
  public function setInscricao_estadual($inscricao_estadual)
  {
    $this->inscricao_estadual = $inscricao_estadual;

    return $this;
  }

  /**
   * Get the value of inscricao_municipal
   */
  public function getInscricao_municipal()
  {
    return $this->inscricao_municipal;
  }

  /**
   * Set the value of inscricao_municipal
   *
   * @return  self
   */
  public function setInscricao_municipal($inscricao_municipal)
  {
    $this->inscricao_municipal = $inscricao_municipal;

    return $this;
  }

  /**
   * Get the value of cetificate_name
   */
  public function getCetificate_name()
  {
    return $this->cetificate_name;
  }

  /**
   * Set the value of cetificate_name
   *
   * @return  self
   */
  public function setCetificate_name($cetificate_name)
  {
    $this->cetificate_name = $cetificate_name;

    return $this;
  }
}
