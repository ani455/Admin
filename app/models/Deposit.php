<?php
/**
 * Deposit Model
 * Handles deposit-related database operations
 */

require_once(__DIR__ . '/../config/database.php');

class Deposit {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all deposits
     */
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM `abramu_deposita` ORDER BY `sthe_date` DESC LIMIT ? OFFSET ?";
        return getRows($this->conn, $sql, "ii", [$limit, $offset]);
    }
    
    /**
     * Get deposit by ID
     */
    public function getById($depositId) {
        $sql = "SELECT * FROM `abramu_deposita` WHERE `abramu_deposita_id` = ?";
        return getRow($this->conn, $sql, "s", [$depositId]);
    }
    
    /**
     * Get user deposits
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM `abramu_deposita` WHERE `byabaharkarta_id` = ? ORDER BY `sthe_date` DESC";
        return getRows($this->conn, $sql, "s", [$userId]);
    }
    
    /**
     * Get pending deposits
     */
    public function getPending($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM `abramu_deposita` WHERE `sthiti` = '0' ORDER BY `sthe_date` DESC LIMIT ? OFFSET ?";
        return getRows($this->conn, $sql, "ii", [$limit, $offset]);
    }
    
    /**
     * Get approved deposits
     */
    public function getApproved($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM `abramu_deposita` WHERE `sthiti` = '1' ORDER BY `sthe_date` DESC LIMIT ? OFFSET ?";
        return getRows($this->conn, $sql, "ii", [$limit, $offset]);
    }
    
    /**
     * Get total deposit amount
     */
    public function getTotalAmount() {
        $sql = "SELECT SUM(`ruchika`) as total FROM `abramu_deposita` WHERE `sthiti` = '1'";
        $result = getRow($this->conn, $sql);
        return $result['total'] ?? 0;
    }
    
    /**
     * Approve deposit
     */
    public function approve($depositId) {
        $sql = "UPDATE `abramu_deposita` SET `sthiti` = '1' WHERE `abramu_deposita_id` = ?";
        return executeUpdate($this->conn, $sql, "s", [$depositId]);
    }
    
    /**
     * Reject deposit
     */
    public function reject($depositId) {
        $sql = "UPDATE `abramu_deposita` SET `sthiti` = '2' WHERE `abramu_deposita_id` = ?";
        return executeUpdate($this->conn, $sql, "s", [$depositId]);
    }
}
?>
