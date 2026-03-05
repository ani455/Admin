<?php
/**
 * User Model
 * Handles user-related database operations
 */

require_once(__DIR__ . '/../config/database.php');

class User {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all users
     */
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM `byabaharkarta` LIMIT ? OFFSET ?";
        return getRows($this->conn, $sql, "ii", [$limit, $offset]);
    }
    
    /**
     * Get user by ID
     */
    public function getById($userId) {
        $sql = "SELECT * FROM `byabaharkarta` WHERE `byabaharkarta_id` = ?";
        return getRow($this->conn, $sql, "s", [$userId]);
    }
    
    /**
     * Get user by username
     */
    public function getByUsername($username) {
        $sql = "SELECT * FROM `byabaharkarta` WHERE `byabaharkarta_hesaru` = ?";
        return getRow($this->conn, $sql, "s", [$username]);
    }
    
    /**
     * Get user count
     */
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM `byabaharkarta`";
        $result = getRow($this->conn, $sql);
        return $result['count'] ?? 0;
    }
    
    /**
     * Search users
     */
    public function search($query) {
        $searchTerm = "%" . $query . "%";
        $sql = "SELECT * FROM `byabaharkarta` WHERE `byabaharkarta_hesaru` LIKE ? OR `mobilnumber` LIKE ? LIMIT 20";
        return getRows($this->conn, $sql, "ss", [$searchTerm, $searchTerm]);
    }
    
    /**
     * Get user balance
     */
    public function getBalance($userId) {
        $sql = "SELECT `khutva_akshara` as balance FROM `byabaharkarta` WHERE `byabaharkarta_id` = ?";
        $result = getRow($this->conn, $sql, "s", [$userId]);
        return $result['balance'] ?? 0;
    }
    
    /**
     * Update user balance
     */
    public function updateBalance($userId, $amount) {
        $sql = "UPDATE `byabaharkarta` SET `khutva_akshara` = ? WHERE `byabaharkarta_id` = ?";
        return executeUpdate($this->conn, $sql, "ds", [$amount, $userId]);
    }
    
    /**
     * Get active users count
     */
    public function getActiveCount() {
        $sql = "SELECT COUNT(*) as count FROM `byabaharkarta` WHERE `sthiti` = '1'";
        $result = getRow($this->conn, $sql);
        return $result['count'] ?? 0;
    }
    
    /**
     * Get total users
     */
    public function getTotalCount() {
        $sql = "SELECT COUNT(*) as count FROM `byabaharkarta`";
        $result = getRow($this->conn, $sql);
        return $result['count'] ?? 0;
    }
}
?>
