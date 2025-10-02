<?php
// Database configuration for Devesh Logistics
class Database {
    private $host = '127.0.0.1';
    private $db_name = 'devesh_logistics';
    private $username = 'root'; // Change this to your MySQL username
    private $password = '@SQlaN_3t7/;';     // Change this to your MySQL password
    private $port = 3306;       // Default MySQL port
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Database connection function for simple usage
function getDBConnection() {
    $database = new Database();
    return $database->getConnection();
}
?>