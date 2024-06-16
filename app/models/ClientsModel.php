<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class ClientsModel extends Connection
{
  private $conn;
  private $id;
  private $name;
  private $apelido;
  private $razao_social;
  private $rg_inscricao;
  private $email;
  private $celular;
  private $cep;
  private $endereco;
  private $documento;
  private $cidade;
  private $numero;
  private $bairro;
  private $complemento;
  private $data_nascimento;
  private $icms;
  private $genero;
  private $deleted;
  private $show_client;
  private $order;
  private $table = 'clients';

  public function __construct($id = null)
  {
    $this->conn = $this->openConnection();

    if ($id !== null) {
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

      $client = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setName($client['name']);
      $this->setApelido($client['apelido']);
      $this->setRazao_social($client['razao_social']);
      $this->setRg_inscricao($client['rg_inscricao']);
      $this->setEmail($client['email']);
      $this->setCelular($client['celular']);
      $this->setCep($client['cep']);
      $this->setEndereco($client['endereco']);
      $this->setDocumento($client['documento']);
      $this->setCidade($client['cidade']);
      $this->setNumero($client['numero']);
      $this->setBairro($client['bairro']);
      $this->setComplemento($client['complemento']);
      $this->setData_nascimento($client['data_nascimento']);
      $this->setIcms($client['icms']);
      $this->setGenero($client['genero']);
      $this->setDeleted($client['deleted']);
      $this->setShow_client($client['show_client']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentClient()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->name = $this->getName();
    $data->apelido = $this->getApelido();
    $data->razao_social = $this->getRazao_social();
    $data->rg_inscricao = $this->getRg_inscricao();
    $data->email = $this->getEmail();
    $data->celular = $this->getCelular();
    $data->cep = $this->getCep();
    $data->endereco = $this->getEndereco();
    $data->documento = $this->getDocumento();
    $data->cidade = $this->getCidade();
    $data->numero = $this->getNumero();
    $data->bairro = $this->getBairro();
    $data->complemento = $this->getComplemento();
    $data->data_nascimento = $this->getData_nascimento();
    $data->icms = $this->getIcms();
    $data->genero = $this->getGenero();
    $data->deleted = $this->getDeleted();
    $data->show_client = $this->getShow_client();
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

    $sql .= " ORDER BY orders ASC";

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
    $sql = "INSERT INTO {$this->table} (name, apelido, razao_social, rg_inscricao, email, celular, cep, endereco, documento, cidade, numero, bairro, complemento, data_nascimento, icms, genero, id_company, orders, show_client) 
            VALUES (:name, :apelido, :razao_social, :rg_inscricao, :email, :celular, :cep, :endereco, :documento, :cidade, :numero, :bairro, :complemento, :data_nascimento, :icms, :genero, :id_company, :order, :show_client)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':name', isset($data['name']) ? $data['name'] : null);
      $stmt->bindValue(':apelido', isset($data['apelido']) ? $data['apelido'] : null);
      $stmt->bindValue(':razao_social', isset($data['razao_social']) ? $data['razao_social'] : null);
      $stmt->bindValue(':rg_inscricao', isset($data['rg_inscricao']) ? $data['rg_inscricao'] : null);
      $stmt->bindValue(':email', isset($data['email']) ? $data['email'] : null);
      $stmt->bindValue(':celular', isset($data['celular']) ? $data['celular'] : null);
      $stmt->bindValue(':cep', isset($data['cep']) ? $data['cep'] : null);
      $stmt->bindValue(':endereco', isset($data['endereco']) ? $data['endereco'] : null);
      $stmt->bindValue(':documento', isset($data['documento']) ? $data['documento'] : null);
      $stmt->bindValue(':cidade', isset($data['cidade']) ? $data['cidade'] : null);
      $stmt->bindValue(':numero', isset($data['numero']) ? $data['numero'] : null);
      $stmt->bindValue(':bairro', isset($data['bairro']) ? $data['bairro'] : null);
      $stmt->bindValue(':complemento', isset($data['complemento']) ? $data['complemento'] : null);
      $stmt->bindValue(':data_nascimento', isset($data['data_nascimento']) ? $data['data_nascimento'] : null);
      $stmt->bindValue(':icms', isset($data['icms']) ? $data['icms'] : null);
      $stmt->bindValue(':genero', isset($data['genero']) ? $data['genero'] : null);
      $stmt->bindValue(':id_company', isset($data['id_company']) ? $data['id_company'] : null);
      $stmt->bindValue(':order', isset($data['order']) ? $data['order'] : null);
      $stmt->bindValue(':show_client', isset($data['show_client']) ? $data['show_client'] : 'S');
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentClient();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET 
      name = :name, 
      apelido = :apelido, 
      razao_social = :razao_social, 
      rg_inscricao = :rg_inscricao, 
      email = :email, 
      celular = :celular, 
      cep = :cep, 
      endereco = :endereco, 
      documento = :documento, 
      cidade = :cidade, 
      numero = :numero, 
      bairro = :bairro, 
      complemento = :complemento, 
      data_nascimento = :data_nascimento, 
      icms = :icms, 
      genero = :genero, 
      deleted = :deleted 
    WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':name', $this->name);
      $stmt->bindValue(':apelido', $this->apelido);
      $stmt->bindValue(':razao_social', $this->razao_social);
      $stmt->bindValue(':rg_inscricao', $this->rg_inscricao);
      $stmt->bindValue(':email', $this->email);
      $stmt->bindValue(':celular', $this->celular);
      $stmt->bindValue(':cep', $this->cep);
      $stmt->bindValue(':endereco', $this->endereco);
      $stmt->bindValue(':documento', $this->documento);
      $stmt->bindValue(':cidade', $this->cidade);
      $stmt->bindValue(':numero', $this->numero);
      $stmt->bindValue(':bairro', $this->bairro);
      $stmt->bindValue(':complemento', $this->complemento);
      $stmt->bindValue(':data_nascimento', $this->data_nascimento);
      $stmt->bindValue(':icms', $this->icms);
      $stmt->bindValue(':genero', $this->genero);
      $stmt->bindValue(':deleted', $this->deleted);
      $stmt->bindValue(':id', $this->id);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentClient();
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
   * Get the value of apelido
   */
  public function getApelido()
  {
    return $this->apelido;
  }

  /**
   * Set the value of apelido
   *
   * @return  self
   */
  public function setApelido($apelido)
  {
    $this->apelido = $apelido;

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
   * Get the value of rg_inscricao
   */
  public function getRg_inscricao()
  {
    return $this->rg_inscricao;
  }

  /**
   * Set the value of rg_inscricao
   *
   * @return  self
   */
  public function setRg_inscricao($rg_inscricao)
  {
    $this->rg_inscricao = $rg_inscricao;

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
   * Get the value of celular
   */
  public function getCelular()
  {
    return $this->celular;
  }

  /**
   * Set the value of celular
   *
   * @return  self
   */
  public function setCelular($celular)
  {
    $this->celular = $celular;

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
   * Get the value of endereco
   */
  public function getEndereco()
  {
    return $this->endereco;
  }

  /**
   * Set the value of endereco
   *
   * @return  self
   */
  public function setEndereco($endereco)
  {
    $this->endereco = $endereco;

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
   * Get the value of complemento
   */
  public function getComplemento()
  {
    return $this->complemento;
  }

  /**
   * Set the value of complemento
   *
   * @return  self
   */
  public function setComplemento($complemento)
  {
    $this->complemento = $complemento;

    return $this;
  }

  /**
   * Get the value of data_nascimento
   */
  public function getData_nascimento()
  {
    return $this->data_nascimento;
  }

  /**
   * Set the value of data_nascimento
   *
   * @return  self
   */
  public function setData_nascimento($data_nascimento)
  {
    $this->data_nascimento = $data_nascimento;

    return $this;
  }

  /**
   * Get the value of icms
   */
  public function getIcms()
  {
    return $this->icms;
  }

  /**
   * Set the value of icms
   *
   * @return  self
   */
  public function setIcms($icms)
  {
    $this->icms = $icms;

    return $this;
  }

  /**
   * Get the value of genero
   */
  public function getGenero()
  {
    return $this->genero;
  }

  /**
   * Set the value of genero
   *
   * @return  self
   */
  public function setGenero($genero)
  {
    $this->genero = $genero;

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
   * Get the value of show_client
   */
  public function getShow_client()
  {
    return $this->show_client;
  }

  /**
   * Set the value of show_client
   *
   * @return  self
   */
  public function setShow_client($show_client)
  {
    $this->show_client = $show_client;

    return $this;
  }

  /**
   * Get the value of order
   */
  public function getOrder()
  {
    return $this->order;
  }

  /**
   * Set the value of order
   *
   * @return  self
   */
  public function setOrder($order)
  {
    $this->order = $order;

    return $this;
  }
}
