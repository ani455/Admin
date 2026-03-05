<?php

/**
 * Database Model Class
 * Handles all database operations with security and error handling
 */

class Database {
    private $conn;
    private $stmt;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Prepare statement safely
     */
    public function prepare($sql) {
        $this->stmt = $this->conn->prepare($sql);
        if (!$this->stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
        return $this;
    }

    /**
     * Bind parameters
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = MYSQLI_TYPE_LONG;
                    break;
                case is_float($value):
                    $type = MYSQLI_TYPE_DOUBLE;
                    break;
                case is_string($value):
                    $type = MYSQLI_TYPE_STRING;
                    break;
                default:
                    $type = MYSQLI_TYPE_STRING;
            }
        }
        
        $this->stmt->bind_param($param, $value, $type);
        return $this;
    }

    /**
     * Execute prepared statement
     */
    public function execute() {
        if (!$this->stmt->execute()) {
            throw new Exception("Execute failed: " . $this->stmt->error);
        }
        return $this;
    }

    /**
     * Get single result
     */
    public function getOne() {
        $result = $this->stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get all results
     */
    public function getAll() {
        $result = $this->stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Get affected rows
     */
    public function getAffectedRows() {
        return $this->stmt->affected_rows;
    }

    /**
     * Get last insert ID
     */
    public function getLastId() {
        return $this->conn->insert_id;
    }

    /**
     * Close statement
     */
    public function close() {
        if ($this->stmt) {
            $this->stmt->close();
        }
    }

    /**
     * Get connection
     */
    public function getConnection() {
        return $this->conn;
    }
}
