<?php
/**
 * Dashboard Model
 * Handles dashboard statistics and overview data
 */

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/User.php');
require_once(__DIR__ . '/Deposit.php');
require_once(__DIR__ . '/Withdrawal.php');
require_once(__DIR__ . '/Bet.php');

class Dashboard {
    private $conn;
    private $userModel;
    private $depositModel;
    private $withdrawalModel;
    private $betModel;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new User($conn);
        $this->depositModel = new Deposit($conn);
        $this->withdrawalModel = new Withdrawal($conn);
        $this->betModel = new Bet($conn);
    }
    
    /**
     * Get dashboard overview
     */
    public function getOverview() {
        return [
            'total_users' => $this->userModel->getTotalCount(),
            'active_users' => $this->userModel->getActiveCount(),
            'total_deposits' => $this->depositModel->getTotalAmount(),
            'total_withdrawals' => $this->withdrawalModel->getTotalAmount(),
            'pending_withdrawals' => $this->withdrawalModel->getPendingTotal(),
            'active_bets' => $this->betModel->getActiveCount(),
            'total_bets_amount' => $this->betModel->getTotalAmount(),
            'winning_amount' => $this->betModel->getWinningAmount(),
        ];
    }
    
    /**
     * Get recent deposits
     */
    public function getRecentDeposits($limit = 5) {
        return $this->depositModel->getAll($limit);
    }
    
    /**
     * Get recent withdrawals
     */
    public function getRecentWithdrawals($limit = 5) {
        return $this->withdrawalModel->getAll($limit);
    }
    
    /**
     * Get pending deposits
     */
    public function getPendingDeposits($limit = 5) {
        return $this->depositModel->getPending($limit);
    }
    
    /**
     * Get pending withdrawals
     */
    public function getPendingWithdrawals($limit = 5) {
        return $this->withdrawalModel->getPending($limit);
    }
    
    /**
     * Get today's statistics
     */
    public function getTodayStats() {
        $today = date('Y-m-d');
        
        $sql = "SELECT 
            COUNT(DISTINCT byabaharkarta_id) as new_users,
            SUM(CASE WHEN sthiti = '1' THEN ruchika ELSE 0 END) as approved_deposits,
            SUM(CASE WHEN sthiti = '1' THEN ruchika ELSE 0 END) as approved_withdrawals,
            COUNT(DISTINCT CASE WHEN sthiti = '0' THEN bajikattuttate_id END) as active_bets
            FROM (
                SELECT byabaharkarta_id, sthiti, ruchika FROM abramu_deposita WHERE DATE(sthe_date) = ?
                UNION ALL
                SELECT byabaharkarta_id, sthiti, ruchika FROM nishpatra_edeyisu WHERE DATE(sthe_date) = ?
            ) combined";
        
        $stmt = executeQuery($this->conn, $sql, "ss", [$today, $today]);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        return $data ?? [];
    }
}
?>
