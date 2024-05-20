<?php

namespace App\Models;

use App\Models\Connection;
use stdClass;

class PermissaoUsuarioModel extends Connection
{
  private $conn;
  private $id;
  private $user;
  private $showMenuRegister;
  private $managerUsers;
  private $managerSuppliers;
  private $managerProdutos;
  private $managerClientes;
  private $managerBrands;
  private $managerCategories;
  private $managerSubcategories;
  private $showMenuBox;
  private $managerBox;
  private $showMenuSales;
  private $managerSales;
  private $showMenuReports;
  private $showReportsSales;
  private $showReportsStock;
  private $showMenuStock;
  private $managerEntries;
  private $showMenuFinance;
  private $managerFinance;
  private $managerAccount;
  private $issueNfe;

  private $table = 'permissoes_usuario';

  public function __construct($id = null)
  {
    $this->conn = $this->openConnection();

    if ($id) {
      $this->setId($id);
      $this->findById();
    }
  }

  public function findById()
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $this->id);
      $stmt->execute();

      $permissaoUsuario = $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function getCurrent()
  {
    $data = new stdClass();
  }

  public function find($filter = [], $limit = null)
  {
    $sql = "SELECT * FROM {$this->table} ";

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
    $sql = "INSERT INTO {$this->table} (
      user, showMenuRegister, managerUsers, managerSuppliers, managerProdutos,
      managerClientes, managerBrands, managerCategories, managerSubcategories,
      showMenuBox, managerBox, showMenuSales, managerSales, showMenuReports,
      showReportsSales, showReportsStock, showMenuStock, managerEntries,
      showMenuFinance, managerFinance, managerAccount, issueNfe
    ) VALUES (:user, :showMenuRegister, :managerUsers, :managerSuppliers, :managerProdutos,
      :managerClientes, :managerBrands, :managerCategories, :managerSubcategories,
      :showMenuBox, :managerBox, :showMenuSales, :managerSales, :showMenuReports,
      :showReportsSales, :showReportsStock, :showMenuStock, :managerEntries,
      :showMenuFinance, :managerFinance, :managerAccount, :issueNfe)";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':user', $data['user']);
      $stmt->bindValue(':showMenuRegister', isset($data['showMenuRegister']) ? $data['showMenuRegister'] : 1);
      $stmt->bindValue(':managerUsers', isset($data['managerUsers']) ? $data['managerUsers'] : 1);
      $stmt->bindValue(':managerSuppliers', isset($data['managerSuppliers']) ? $data['managerSuppliers'] : 1);
      $stmt->bindValue(':managerProdutos', isset($data['managerProdutos']) ? $data['managerProdutos'] : 1);
      $stmt->bindValue(':managerClientes', isset($data['managerClientes']) ? $data['managerClientes'] : 1);
      $stmt->bindValue(':managerBrands', isset($data['managerBrands']) ? $data['managerBrands'] : 1);
      $stmt->bindValue(':managerCategories', isset($data['managerCategories']) ? $data['managerCategories'] : 1);
      $stmt->bindValue(':managerSubcategories', isset($data['managerSubcategories']) ? $data['managerSubcategories'] : 1);
      $stmt->bindValue(':showMenuBox', isset($data['showMenuBox']) ? $data['showMenuBox'] : 1);
      $stmt->bindValue(':managerBox', isset($data['managerBox']) ? $data['managerBox'] : 1);
      $stmt->bindValue(':showMenuSales', isset($data['showMenuSales']) ? $data['showMenuSales'] : 1);
      $stmt->bindValue(':managerSales', isset($data['managerSales']) ? $data['managerSales'] : 1);
      $stmt->bindValue(':showMenuReports', isset($data['showMenuReports']) ? $data['showMenuReports'] : 1);
      $stmt->bindValue(':showReportsSales', isset($data['showReportsSales']) ? $data['showReportsSales'] : 1);
      $stmt->bindValue(':showReportsStock', isset($data['showReportsStock']) ? $data['showReportsStock'] : 1);
      $stmt->bindValue(':showMenuStock', isset($data['showMenuStock']) ? $data['showMenuStock'] : 1);
      $stmt->bindValue(':managerEntries', isset($data['managerEntries']) ? $data['managerEntries'] : 1);
      $stmt->bindValue(':showMenuFinance', isset($data['showMenuFinance']) ? $data['showMenuFinance'] : 1);
      $stmt->bindValue(':managerFinance', isset($data['managerFinance']) ? $data['managerFinance'] : 1);
      $stmt->bindValue(':managerAccount', isset($data['managerAccount']) ? $data['managerAccount'] : 1);
      $stmt->bindValue(':issueNfe', isset($data['issueNfe']) ? $data['issueNfe'] : 1);
      $stmt->execute();
    } catch (\PDOException $e) {

      var_dump($e->getMessage());
      die();

      echo $e->getMessage();
    }
  }

  public function update($data)
  {
    $sql = "UPDATE {$this->table} SET
      showMenuRegister = :showMenuRegister,
      managerUsers = :managerUsers,
      managerSuppliers = :managerSuppliers,
      managerProdutos = :managerProdutos,
      managerClientes = :managerClientes,
      managerBrands = :managerBrands,
      managerCategories = :managerCategories,
      managerSubcategories = :managerSubcategories,
      showMenuBox = :showMenuBox,
      managerBox = :managerBox,
      showMenuSales = :showMenuSales,
      managerSales = :managerSales,
      showMenuReports = :showMenuReports,
      showReportsSales = :showReportsSales,
      showReportsStock = :showReportsStock,
      showMenuStock = :showMenuStock,
      managerEntries = :managerEntries,
      showMenuFinance = :showMenuFinance,
      managerFinance = :managerFinance,
      managerAccount = :managerAccount,
      issueNfe = :issueNfe
    WHERE id = :id";

    foreach ($data as $column => $value) {
      $this->$column = $value;
    }

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(':showMenuRegister', $this->showMenuRegister);
      $stmt->bindValue(':managerUsers', $this->managerUsers);
      $stmt->bindValue(':managerSuppliers', $this->managerSuppliers);
      $stmt->bindValue(':managerProdutos', $this->managerProdutos);
      $stmt->bindValue(':managerClientes', $this->managerClientes);
      $stmt->bindValue(':managerBrands', $this->managerBrands);
      $stmt->bindValue(':managerCategories', $this->managerCategories);
      $stmt->bindValue(':managerSubcategories', $this->managerSubcategories);
      $stmt->bindValue(':showMenuBox', $this->showMenuBox);
      $stmt->bindValue(':managerBox', $this->managerBox);
      $stmt->bindValue(':showMenuSales', $this->showMenuSales);
      $stmt->bindValue(':managerSales', $this->managerSales);
      $stmt->bindValue(':showMenuReports', $this->showMenuReports);
      $stmt->bindValue(':showReportsSales', $this->showReportsSales);
      $stmt->bindValue(':showReportsStock', $this->showReportsStock);
      $stmt->bindValue(':showMenuStock', $this->showMenuStock);
      $stmt->bindValue(':managerEntries', $this->managerEntries);
      $stmt->bindValue(':showMenuFinance', $this->showMenuFinance);
      $stmt->bindValue(':managerFinance', $this->managerFinance);
      $stmt->bindValue(':managerAccount', $this->managerAccount);
      $stmt->bindValue(':issueNfe', $this->issueNfe);
      $stmt->bindValue(':id', $this->id);
      $stmt->execute();

      return $stmt->rowCount();
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
   * Get the value of showMenuRegister
   */
  public function getShowMenuRegister()
  {
    return $this->showMenuRegister;
  }

  /**
   * Set the value of showMenuRegister
   *
   * @return  self
   */
  public function setShowMenuRegister($showMenuRegister)
  {
    $this->showMenuRegister = $showMenuRegister;

    return $this;
  }

  /**
   * Get the value of managerUsers
   */
  public function getManagerUsers()
  {
    return $this->managerUsers;
  }

  /**
   * Set the value of managerUsers
   *
   * @return  self
   */
  public function setManagerUsers($managerUsers)
  {
    $this->managerUsers = $managerUsers;

    return $this;
  }

  /**
   * Get the value of managerSuppliers
   */
  public function getManagerSuppliers()
  {
    return $this->managerSuppliers;
  }

  /**
   * Set the value of managerSuppliers
   *
   * @return  self
   */
  public function setManagerSuppliers($managerSuppliers)
  {
    $this->managerSuppliers = $managerSuppliers;

    return $this;
  }

  /**
   * Get the value of managerProdutos
   */
  public function getManagerProdutos()
  {
    return $this->managerProdutos;
  }

  /**
   * Set the value of managerProdutos
   *
   * @return  self
   */
  public function setManagerProdutos($managerProdutos)
  {
    $this->managerProdutos = $managerProdutos;

    return $this;
  }

  /**
   * Get the value of managerClientes
   */
  public function getManagerClientes()
  {
    return $this->managerClientes;
  }

  /**
   * Set the value of managerClientes
   *
   * @return  self
   */
  public function setManagerClientes($managerClientes)
  {
    $this->managerClientes = $managerClientes;

    return $this;
  }

  /**
   * Get the value of managerBrands
   */
  public function getManagerBrands()
  {
    return $this->managerBrands;
  }

  /**
   * Set the value of managerBrands
   *
   * @return  self
   */
  public function setManagerBrands($managerBrands)
  {
    $this->managerBrands = $managerBrands;

    return $this;
  }

  /**
   * Get the value of managerCategories
   */
  public function getManagerCategories()
  {
    return $this->managerCategories;
  }

  /**
   * Set the value of managerCategories
   *
   * @return  self
   */
  public function setManagerCategories($managerCategories)
  {
    $this->managerCategories = $managerCategories;

    return $this;
  }

  /**
   * Get the value of managerSubcategories
   */
  public function getManagerSubcategories()
  {
    return $this->managerSubcategories;
  }

  /**
   * Set the value of managerSubcategories
   *
   * @return  self
   */
  public function setManagerSubcategories($managerSubcategories)
  {
    $this->managerSubcategories = $managerSubcategories;

    return $this;
  }

  /**
   * Get the value of showMenuBox
   */
  public function getShowMenuBox()
  {
    return $this->showMenuBox;
  }

  /**
   * Set the value of showMenuBox
   *
   * @return  self
   */
  public function setShowMenuBox($showMenuBox)
  {
    $this->showMenuBox = $showMenuBox;

    return $this;
  }

  /**
   * Get the value of managerBox
   */
  public function getManagerBox()
  {
    return $this->managerBox;
  }

  /**
   * Set the value of managerBox
   *
   * @return  self
   */
  public function setManagerBox($managerBox)
  {
    $this->managerBox = $managerBox;

    return $this;
  }

  /**
   * Get the value of showMenuSales
   */
  public function getShowMenuSales()
  {
    return $this->showMenuSales;
  }

  /**
   * Set the value of showMenuSales
   *
   * @return  self
   */
  public function setShowMenuSales($showMenuSales)
  {
    $this->showMenuSales = $showMenuSales;

    return $this;
  }

  /**
   * Get the value of managerSales
   */
  public function getManagerSales()
  {
    return $this->managerSales;
  }

  /**
   * Set the value of managerSales
   *
   * @return  self
   */
  public function setManagerSales($managerSales)
  {
    $this->managerSales = $managerSales;

    return $this;
  }

  /**
   * Get the value of showMenuReports
   */
  public function getShowMenuReports()
  {
    return $this->showMenuReports;
  }

  /**
   * Set the value of showMenuReports
   *
   * @return  self
   */
  public function setShowMenuReports($showMenuReports)
  {
    $this->showMenuReports = $showMenuReports;

    return $this;
  }

  /**
   * Get the value of showReportsSales
   */
  public function getShowReportsSales()
  {
    return $this->showReportsSales;
  }

  /**
   * Set the value of showReportsSales
   *
   * @return  self
   */
  public function setShowReportsSales($showReportsSales)
  {
    $this->showReportsSales = $showReportsSales;

    return $this;
  }

  /**
   * Get the value of showReportsStock
   */
  public function getShowReportsStock()
  {
    return $this->showReportsStock;
  }

  /**
   * Set the value of showReportsStock
   *
   * @return  self
   */
  public function setShowReportsStock($showReportsStock)
  {
    $this->showReportsStock = $showReportsStock;

    return $this;
  }

  /**
   * Get the value of showMenuStock
   */
  public function getShowMenuStock()
  {
    return $this->showMenuStock;
  }

  /**
   * Set the value of showMenuStock
   *
   * @return  self
   */
  public function setShowMenuStock($showMenuStock)
  {
    $this->showMenuStock = $showMenuStock;

    return $this;
  }

  /**
   * Get the value of managerEntries
   */
  public function getManagerEntries()
  {
    return $this->managerEntries;
  }

  /**
   * Set the value of managerEntries
   *
   * @return  self
   */
  public function setManagerEntries($managerEntries)
  {
    $this->managerEntries = $managerEntries;

    return $this;
  }

  /**
   * Get the value of showMenuFinance
   */
  public function getShowMenuFinance()
  {
    return $this->showMenuFinance;
  }

  /**
   * Set the value of showMenuFinance
   *
   * @return  self
   */
  public function setShowMenuFinance($showMenuFinance)
  {
    $this->showMenuFinance = $showMenuFinance;

    return $this;
  }

  /**
   * Get the value of managerFinance
   */
  public function getManagerFinance()
  {
    return $this->managerFinance;
  }

  /**
   * Set the value of managerFinance
   *
   * @return  self
   */
  public function setManagerFinance($managerFinance)
  {
    $this->managerFinance = $managerFinance;

    return $this;
  }

  /**
   * Get the value of managerAccount
   */
  public function getManagerAccount()
  {
    return $this->managerAccount;
  }

  /**
   * Set the value of managerAccount
   *
   * @return  self
   */
  public function setManagerAccount($managerAccount)
  {
    $this->managerAccount = $managerAccount;

    return $this;
  }

  /**
   * Get the value of user
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Set the value of user
   *
   * @return  self
   */
  public function setUser($user)
  {
    $this->user = $user;

    return $this;
  }

  /**
   * Get the value of issueNfe
   */
  public function getIssueNfe()
  {
    return $this->issueNfe;
  }

  /**
   * Set the value of issueNfe
   *
   * @return  self
   */
  public function setIssueNfe($issueNfe)
  {
    $this->issueNfe = $issueNfe;

    return $this;
  }
}
