<?php

/**
 * Transaction Model Class
 * Handles withdrawals, deposits, and all payment transactions
 */

class Transaction {
    private $conn;
    private $table = 'transactions';
    private $withdrawTable = 'withdraw_request';
    private $depositTable = 'deposit_request';

    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Create withdrawal request
     */
    public function createWithdrawal($userId, $amount, $bankDetails) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO " . $this->withdrawTable . 
                " (user_id, amount, bank_name, account_number, status, created_at) 
                 VALUES (?, ?, ?, ?, 'pending', NOW())"
            );

            $stmt->bind_param(
                "idss",
                $userId,
                $amount,
                $bankDetails['bank_name'],
                $bankDetails['account_number']
            );

            if ($stmt->execute()) {
                return ['success' => true, 'id' => $this->conn->insert_id];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error creating withdrawal: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get all withdrawals with pagination
     */
    public function getWithdrawals($limit = 20, $offset = 0, $status = '') {
        try {
            $query = "SELECT * FROM " . $this->withdrawTable;
            
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
            error_log("Error getting withdrawals: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get withdrawal by ID
     */
    public function getWithdrawalById($withdrawalId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->withdrawTable . " WHERE id = ?");
            $stmt->bind_param("i", $withdrawalId);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting withdrawal: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update withdrawal status
     */
    public function updateWithdrawalStatus($withdrawalId, $status) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE " . $this->withdrawTable . " SET status = ?, updated_at = NOW() WHERE id = ?"
            );
            $stmt->bind_param("si", $status, $withdrawalId);
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error updating withdrawal: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Create deposit
     */
    public function createDeposit($userId, $amount, $paymentMethod) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO " . $this->depositTable . 
                " (user_id, amount, payment_method, status, created_at) 
                 VALUES (?, ?, ?, 'pending', NOW())"
            );

            $stmt->bind_param(
                "ids",
                $userId,
                $amount,
                $paymentMethod
            );

            if ($stmt->execute()) {
                return ['success' => true, 'id' => $this->conn->insert_id];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error creating deposit: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get all deposits with pagination
     */
    public function getDeposits($limit = 20, $offset = 0, $status = '') {
        try {
            $query = "SELECT * FROM " . $this->depositTable;
            
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
            error_log("Error getting deposits: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update deposit status
     */
    public function updateDepositStatus($depositId, $status) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE " . $this->depositTable . " SET status = ?, updated_at = NOW() WHERE id = ?"
            );
            $stmt->bind_param("si", $status, $depositId);
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error updating deposit: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get transaction history
     */
    public function getHistory($userId, $limit = 20, $offset = 0) {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM " . $this->table . " WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?"
            );
            $stmt->bind_param("iii", $userId, $limit, $offset);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting transaction history: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count pending withdrawals
     */
    public function countPendingWithdrawals() {
        try {
            $result = $this->conn->query(
                "SELECT COUNT(*) as total FROM " . $this->withdrawTable . " WHERE status = 'pending'"
            );
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (Exception $e) {
            error_log("Error counting pending withdrawals: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Count pending deposits
     */
    public function countPendingDeposits() {
        try {
            $result = $this->conn->query(
                "SELECT COUNT(*) as total FROM " . $this->depositTable . " WHERE status = 'pending'"
            );
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (Exception $e) {
            error_log("Error counting pending deposits: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total withdrawal amount
     */
    public function getTotalWithdrawnToday() {
        try {
            $result = $this->conn->query(
                "SELECT SUM(amount) as total FROM " . $this->withdrawTable . 
                " WHERE status = 'approved' AND DATE(created_at) = CURDATE()"
            );
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error getting total withdrawn: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total deposit amount
     */
    public function getTotalDepositedToday() {
        try {
            $result = $this->conn->query(
                "SELECT SUM(amount) as total FROM " . $this->depositTable . 
                " WHERE status = 'approved' AND DATE(created_at) = CURDATE()"
            );
            $row = $result->fetch_assoc();
            return $row['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error getting total deposited: " . $e->getMessage());
            return 0;
        }
    }
}
