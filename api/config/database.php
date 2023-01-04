<?php

class Database
{

  // specify your own database credentials
  private $hostname = "localhost";
  private $port     = 3306;
  private $database = "api_db";
  private $username = "root";
  private $password = "";
  public $conn;

  // get the database connection
  public function getConnection()
  {

    $this->conn = null;

    try {
      $this->conn = new PDO("mysql:host=" . $this->hostname . ";port=" . $this->port . ";dbname=" . $this->database, $this->username, $this->password);
      $this->conn->exec("set names utf8");
    } catch (PDOException $exception) {
      $results["error"] = true;
      $results["users"] = "Erro conectando ao banco de dados: é " . $exception->getMessage();
      return $results;
    }
    return $this->conn;
  }
}

