<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class PermissoesSistemaModel extends Connection
{
  private $conn;
  private $id;
  private $id_user;
  private $id_company;
  private $responsavel;
  private $telefone;
  private $email;
  private $valor_mensal;
  private $limite_nfce;
  private $limite_nfe;
  private $limite_empresas;
  private $limite_usuarios;
  private $limite_produtos;
  private $limite_clientes;
  private $date_expiration;
  private $table = 'permissoes_sistema';

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

      $balance = $stmt->fetch(\PDO::FETCH_ASSOC);

      $this->setId_user($balance['id_user']);
      $this->setId_company($balance['id_company']);
      $this->setResponsavel($balance['responsavel']);
      $this->setTelefone($balance['telefone']);
      $this->setEmail($balance['email']);
      $this->setValor_mensal($balance['valor_mensal']);
      $this->setLimite_nfce($balance['limite_nfce']);
      $this->setLimite_nfe($balance['limite_nfe']);
      $this->setLimite_empresas($balance['limite_empresas']);
      $this->setLimite_usuarios($balance['limite_usuarios']);
      $this->setLimite_produtos($balance['limite_produtos']);
      $this->setLimite_clientes($balance['limite_clientes']);
      $this->setDate_expiration($balance['date_expiration']);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrentPermission()
  {
    $data = new stdClass();
    $data->id = $this->getId();
    $data->id_user = $this->getId_user();
    $data->id_company = $this->getId_company();
    $data->responsavel = $this->getResponsavel();
    $data->telefone = $this->getTelefone();
    $data->email = $this->getEmail();
    $data->valor_mensal = $this->getValor_mensal();
    $data->limite_nfce = $this->getLimite_nfce();
    $data->limite_nfe = $this->getLimite_nfe();
    $data->limite_empresas = $this->getLimite_empresas();
    $data->limite_usuarios = $this->getLimite_usuarios();
    $data->limite_produtos = $this->getLimite_produtos();
    $data->limite_clientes = $this->getLimite_clientes();
    $data->date_expiration = $this->getDate_expiration();
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
    $sql = "INSERT INTO {$this->table} (id_user, id_company, responsavel, telefone, email, valor_mensal, limite_nfce, limite_nfe, limite_empresas, limite_usuarios, limite_produtos, limite_clientes, date_expiration) 
    VALUES (:id_user, :id_company, :responsavel, :telefone, :email, :valor_mensal, :limite_nfce, :limite_nfe, :limite_empresas, :limite_usuarios, :limite_produtos, :limite_clientes, :date_expiration)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id_user', $data['id_user']);
      $stmt->bindParam(':id_company', $data['id_company']);
      $stmt->bindParam(':responsavel', $data['responsavel']);
      $stmt->bindParam(':telefone', $data['telefone']);
      $stmt->bindParam(':email', $data['email']);
      $stmt->bindParam(':valor_mensal', $data['valor_mensal']);
      $stmt->bindParam(':limite_nfce', $data['limite_nfce']);
      $stmt->bindParam(':limite_nfe', $data['limite_nfe']);
      $stmt->bindParam(':limite_empresas', $data['limite_empresas']);
      $stmt->bindParam(':limite_usuarios', $data['limite_usuarios']);
      $stmt->bindParam(':limite_produtos', $data['limite_produtos']);
      $stmt->bindParam(':limite_clientes', $data['limite_clientes']);
      $stmt->bindParam(':date_expiration', $data['date_expiration']);
      $stmt->execute();

      $this->setId($this->conn->lastInsertId());
      $this->getById();
      return $this->getCurrentPermission();
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} 
            SET 
              responsavel = :responsavel, 
              telefone = :telefone, 
              email = :email, 
              valor_mensal = :valor_mensal, 
              limite_nfce = :limite_nfce, 
              limite_nfe = :limite_nfe, 
              limite_empresas = :limite_empresas, 
              limite_usuarios = :limite_usuarios, 
              limite_produtos = :limite_produtos, 
              limite_clientes = :limite_clientes, 
              date_expiration = :date_expiration
            WHERE id = :id";


    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $this->id);
      $stmt->bindParam(':responsavel', $this->responsavel);
      $stmt->bindParam(':telefone', $this->telefone);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':valor_mensal', $this->valor_mensal);
      $stmt->bindParam(':limite_nfce', $this->limite_nfce);
      $stmt->bindParam(':limite_nfe', $this->limite_nfe);
      $stmt->bindParam(':limite_empresas', $this->limite_empresas);
      $stmt->bindParam(':limite_usuarios', $this->limite_usuarios);
      $stmt->bindParam(':limite_produtos', $this->limite_produtos);
      $stmt->bindParam(':limite_clientes', $this->limite_clientes);
      $stmt->bindParam(':date_expiration', $this->date_expiration);
      $stmt->execute();

      $this->getById();
      return $this->getCurrentPermission();
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
   * Get the value of id_user
   */
  public function getId_user()
  {
    return $this->id_user;
  }

  /**
   * Set the value of id_user
   *
   * @return  self
   */
  public function setId_user($id_user)
  {
    $this->id_user = $id_user;

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
   * Get the value of responsavel
   */
  public function getResponsavel()
  {
    return $this->responsavel;
  }

  /**
   * Set the value of responsavel
   *
   * @return  self
   */
  public function setResponsavel($responsavel)
  {
    $this->responsavel = $responsavel;

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
   * Get the value of valor_mensal
   */
  public function getValor_mensal()
  {
    return $this->valor_mensal;
  }

  /**
   * Set the value of valor_mensal
   *
   * @return  self
   */
  public function setValor_mensal($valor_mensal)
  {
    $this->valor_mensal = $valor_mensal;

    return $this;
  }

  /**
   * Get the value of limite_nfce
   */
  public function getLimite_nfce()
  {
    return $this->limite_nfce;
  }

  /**
   * Set the value of limite_nfce
   *
   * @return  self
   */
  public function setLimite_nfce($limite_nfce)
  {
    $this->limite_nfce = $limite_nfce;

    return $this;
  }

  /**
   * Get the value of limite_nfe
   */
  public function getLimite_nfe()
  {
    return $this->limite_nfe;
  }

  /**
   * Set the value of limite_nfe
   *
   * @return  self
   */
  public function setLimite_nfe($limite_nfe)
  {
    $this->limite_nfe = $limite_nfe;

    return $this;
  }

  /**
   * Get the value of limite_empresas
   */
  public function getLimite_empresas()
  {
    return $this->limite_empresas;
  }

  /**
   * Set the value of limite_empresas
   *
   * @return  self
   */
  public function setLimite_empresas($limite_empresas)
  {
    $this->limite_empresas = $limite_empresas;

    return $this;
  }

  /**
   * Get the value of limite_usuarios
   */
  public function getLimite_usuarios()
  {
    return $this->limite_usuarios;
  }

  /**
   * Set the value of limite_usuarios
   *
   * @return  self
   */
  public function setLimite_usuarios($limite_usuarios)
  {
    $this->limite_usuarios = $limite_usuarios;

    return $this;
  }

  /**
   * Get the value of limite_produtos
   */
  public function getLimite_produtos()
  {
    return $this->limite_produtos;
  }

  /**
   * Set the value of limite_produtos
   *
   * @return  self
   */
  public function setLimite_produtos($limite_produtos)
  {
    $this->limite_produtos = $limite_produtos;

    return $this;
  }

  /**
   * Get the value of limite_clientes
   */
  public function getLimite_clientes()
  {
    return $this->limite_clientes;
  }

  /**
   * Set the value of limite_clientes
   *
   * @return  self
   */
  public function setLimite_clientes($limite_clientes)
  {
    $this->limite_clientes = $limite_clientes;

    return $this;
  }

  /**
   * Get the value of date_expiration
   */
  public function getDate_expiration()
  {
    return $this->date_expiration;
  }

  /**
   * Set the value of date_expiration
   *
   * @return  self
   */
  public function setDate_expiration($date_expiration)
  {
    $this->date_expiration = $date_expiration;

    return $this;
  }
}
