<?php
require_once __DIR__ . '/../config/config.php';

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Get database instance (Singleton pattern)
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Get database connection
    public function getConnection() {
        return $this->conn;
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    private function __wakeup() {}
}

// Helper functions for database operations
function executeQuery($sql, $params = []) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        error_log("Query execution failed: " . $e->getMessage());
        return false;
    }
}

function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetchAll() : false;
}

function insert($table, $data) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_fill(0, count($data), "?"));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $conn->lastInsertId();
    } catch(PDOException $e) {
        error_log("Insert failed: " . $e->getMessage());
        return false;
    }
}

function update($table, $data, $where, $whereParams = []) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $set = implode(" = ?, ", array_keys($data)) . " = ?";
        $sql = "UPDATE $table SET $set WHERE $where";
        
        $params = array_merge(array_values($data), $whereParams);
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->rowCount();
    } catch(PDOException $e) {
        error_log("Update failed: " . $e->getMessage());
        return false;
    }
}

function delete($table, $where, $params = []) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->rowCount();
    } catch(PDOException $e) {
        error_log("Delete failed: " . $e->getMessage());
        return false;
    }
}
?> 