<?php
/**
 * Bet Model
 * Handles bet-related database operations
 */

require_once(__DIR__ . '/../config/database.php');

class Bet {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Get all bets
     */
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT * FROM `bajikattuttate` ORDER BY `sthe_date` DESC LIMIT ? OFFSET ?";
        return getRows($this->conn, $sql, "ii", [$limit, $offset]);
    }
    
    /**
     * Get bet by ID
     */
    public function getById($betId) {
        $sql = "SELECT * FROM `bajikattuttate` WHERE `bajikattuttate_id` = ?";
        return getRow($this->conn, $sql, "s", [$betId]);
    }
    
    /**
     * Get user bets
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM `bajikattuttate` WHERE `byabaharkarta_id` = ? ORDER BY `sthe_date` DESC";
        return getRows($this->conn, $sql, "s", [$userId]);
    }
    
    /**
     * Get active bets count
     */
    public function getActiveCount() {
        $sql = "SELECT COUNT(*) as count FROM `bajikattuttate` WHERE `sthiti` = '0'";
        $result = getRow($this->conn, $sql);
        return $result['count'] ?? 0;
    }
    
    /**
     * Get total bets amount
     */
    public function getTotalAmount() {
        $sql = "SELECT SUM(`ketebida`) as total FROM `bajikattuttate`";
        $result = getRow($this->conn, $sql);
        return $result['total'] ?? 0;
    }
    
    /**
     * Get winning bets
     */
    public function getWinningAmount() {
        $sql = "SELECT SUM(`bidavaru_ruchika`) as total FROM `bajikattuttate` WHERE `sthiti` = '1'";
        $result = getRow($this->conn, $sql);
        return $result['total'] ?? 0;
    }
    
    /**
     * Settle bet
     */
    public function settle($betId, $winAmount) {
        $sql = "UPDATE `bajikattuttate` SET `sthiti` = '1', `bidavaru_ruchika` = ? WHERE `bajikattuttate_id` = ?";
        return executeUpdate($this->conn, $sql, "ds", [$winAmount, $betId]);
    }
}
?>
