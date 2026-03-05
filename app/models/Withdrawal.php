<?php
/**
 * Withdrawal Model
 * Handles withdrawal-related database operations
 */

require_once(__DIR__ . '/../config/database.php');

class Withdrawal {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all withdrawals
     */
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM `nishpatra_edeyisu` ORDER BY `sthe_date` DESC LIMIT ? OFFSET ?";
        return getRows($this->conn, $sql, "ii", [$limit, $offset]);
    }
    
    /**
     * Get withdrawal by ID
     */
    public function getById($withdrawalId) {
        $sql = "SELECT * FROM `nishpatra_edeyisu` WHERE `nishpatra_edeyisu_id` = ?";
        return getRow($this->conn, $sql, "s", [$withdrawalId]);
    }
    
    /**
     * Get user withdrawals
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM `nishpatra_edeyisu` WHERE `byabaharkarta_id` = ? ORDER BY `sthe_date` DESC";
        return getRows($this->conn, $sql, "s", [$userId]);
    }
    
    /**
     * Get pending withdrawals
     */
    public function getPending($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM `nishpatra_edeyisu` WHERE `sthiti` = '0' ORDER BY `sthe_date` DESC LIMIT ? OFFSET ?";
        return getRows($this->conn, $sql, "ii", [$limit, $offset]);
    }
    
    /**
     * Get total withdrawal amount
     */
    public function getTotalAmount() {
        $sql = "SELECT SUM(`ruchika`) as total FROM `nishpatra_edeyisu` WHERE `sthiti` = '1'";
        $result = getRow($this->conn, $sql);
        return $result['total'] ?? 0;
    }
    
    /**
     * Get pending total
     */
    public function getPendingTotal() {
        $sql = "SELECT SUM(`ruchika`) as total FROM `nishpatra_edeyisu` WHERE `sthiti` = '0'";
        $result = getRow($this->conn, $sql);
        return $result['total'] ?? 0;
    }
    
    /**
     * Approve withdrawal
     */
    public function approve($withdrawalId) {
        $sql = "UPDATE `nishpatra_edeyisu` SET `sthiti` = '1' WHERE `nishpatra_edeyisu_id` = ?";
        return executeUpdate($this->conn, $sql, "s", [$withdrawalId]);
    }
    
    /**
     * Reject withdrawal
     */
    public function reject($withdrawalId) {
        $sql = "UPDATE `nishpatra_edeyisu` SET `sthiti` = '2' WHERE `nishpatra_edeyisu_id` = ?";
        return executeUpdate($this->conn, $sql, "s", [$withdrawalId]);
    }
}
?>
