<?php
class Database
{
    private $host = 'localhost';
    private $dbname = 'uas_pbdprak';
    private $username = 'root';
    private $password = '';
    private $pdo;

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Koneksi database gagal: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query gagal: " . $e->getMessage());
        }
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function execute($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function callProcedure($spName, $params = [])
    {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $stmt = $this->pdo->prepare("CALL {$spName}({$placeholders})");
        $stmt->execute(array_values($params));
        return $stmt->fetchAll();
    }

    public function callFunction($fnName, $params = [])
    {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $stmt = $this->pdo->prepare("SELECT {$fnName}({$placeholders}) AS result");
        $stmt->execute(array_values($params));
        $row = $stmt->fetch();
        return $row['result'] ?? null;
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollBack();
    }


}
?>