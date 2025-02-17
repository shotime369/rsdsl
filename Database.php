<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = 'localhost'; // Database host
        $db = 'loginweb';    // Database name
        $user = 'shona';     // Database username
        $pass = '1234';      // Database password
        $charset = 'utf8mb4'; // Charset

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Handle connection error
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    // Singleton Pattern: Get the PDO instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
?>
