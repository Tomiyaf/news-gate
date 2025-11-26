<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'berita_db';
    private $username = 'root';
    private $password = 'tomy#root';
    private $port = 3307;
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            error_log("[DB] Connection successful to database: " . $this->db_name);
        } catch(PDOException $e) {
            error_log("[DB ERROR] Connection failed: " . $e->getMessage());
            die("<div style='padding:20px;background:#f44336;color:white;'>Database Connection Error: " . $e->getMessage() . "</div>");
        }

        return $this->conn;
    }
}
