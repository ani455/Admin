<?php

/**
 * Game Model Class
 * Handles betting, games, and game results
 */

class Game {
    private $conn;
    private $betsTable = 'bet_kacha';
    private $gamesTable = 'games';

    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Create a bet
     */
    public function createBet($userId, $gameId, $amount, $prediction) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO " . $this->betsTable . 
                " (user_id, game_id, amount, prediction, status, created_at) 
                 VALUES (?, ?, ?, ?, 'active', NOW())"
            );

            $stmt->bind_param(
                "iids",
                $userId,
                $gameId,
                $amount,
                $prediction
            );

            if ($stmt->execute()) {
                return ['success' => true, 'id' => $this->conn->insert_id];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error creating bet: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get all bets with pagination
     */
    public function getAllBets($limit = 20, $offset = 0, $status = '') {
        try {
            $query = "SELECT * FROM " . $this->betsTable;
            
            if (!empty($status)) {
                $query .= " WHERE status = ?";
                $stmt = $this->conn->prepare($query . " ORDER BY created_at DESC LIMIT ? OFFSET ?");
                $stmt->bind_param("sii", $status, $limit, $offset);
            } else {
                $stmt = $this->conn->prepare($query . " ORDER BY created_at DESC LIMIT ? OFFSET ?");
                $stmt->bind_param("ii", $limit, $offset);
            }
            
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting bets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user bets
     */
    public function getUserBets($userId, $limit = 20, $offset = 0) {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM " . $this->betsTable . 
                " WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?"
            );
            $stmt->bind_param("iii", $userId, $limit, $offset);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting user bets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get bet by ID
     */
    public function getBetById($betId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->betsTable . " WHERE id = ?");
            $stmt->bind_param("i", $betId);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting bet: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Settle bet (mark as won/lost)
     */
    public function settleBet($betId, $result, $payout = 0) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE " . $this->betsTable . 
                " SET status = ?, result = ?, payout = ?, settled_at = NOW() WHERE id = ?"
            );
            
            $status = ($result === 'win') ? 'settled' : 'settled';
            $stmt->bind_param("ssdi", $status, $result, $payout, $betId);
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error settling bet: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Cancel bet
     */
    public function cancelBet($betId, $reason = '') {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE " . $this->betsTable . 
                " SET status = 'cancelled', cancellation_reason = ?, cancelled_at = NOW() WHERE id = ?"
            );
            $stmt->bind_param("si", $reason, $betId);
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error cancelling bet: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get recent bets
     */
    public function getRecentBets($limit = 10) {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM " . $this->betsTable . " ORDER BY created_at DESC LIMIT ?"
            );
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting recent bets: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get bet statistics
     */
    public function getBetStats() {
        try {
            $stats = [];

            // Total bets
            $result = $this->conn->query("SELECT COUNT(*) as total FROM " . $this->betsTable);
            $stats['total_bets'] = $result->fetch_assoc()['total'];

            // Total bets amount
            $result = $this->conn->query("SELECT SUM(amount) as total FROM " . $this->betsTable);
            $row = $result->fetch_assoc();
            $stats['total_amount'] = $row['total'] ?? 0;

            // Won bets
            $result = $this->conn->query(
                "SELECT COUNT(*) as total FROM " . $this->betsTable . " WHERE result = 'win'"
            );
            $stats['winning_bets'] = $result->fetch_assoc()['total'];

            // Lost bets
            $result = $this->conn->query(
                "SELECT COUNT(*) as total FROM " . $this->betsTable . " WHERE result = 'loss'"
            );
            $stats['losing_bets'] = $result->fetch_assoc()['total'];

            // Total payout
            $result = $this->conn->query("SELECT SUM(payout) as total FROM " . $this->betsTable);
            $row = $result->fetch_assoc();
            $stats['total_payout'] = $row['total'] ?? 0;

            return $stats;
        } catch (Exception $e) {
            error_log("Error getting bet stats: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count active bets
     */
    public function countActiveBets() {
        try {
            $result = $this->conn->query(
                "SELECT COUNT(*) as total FROM " . $this->betsTable . " WHERE status = 'active'"
            );
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (Exception $e) {
            error_log("Error counting active bets: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get today's bets
     */
    public function getTodaysBets() {
        try {
            $result = $this->conn->query(
                "SELECT COUNT(*) as total FROM " . $this->betsTable . " WHERE DATE(created_at) = CURDATE()"
            );
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (Exception $e) {
            error_log("Error getting today's bets: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get today's revenue from bets
     */
    public function getTodaysRevenue() {
        try {
            $result = $this->conn->query(
                "SELECT SUM(amount) as total FROM " . $this->betsTable . " WHERE DATE(created_at) = CURDATE()"
            );
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error getting today's revenue: " . $e->getMessage());
            return 0;
        }
    }
}
