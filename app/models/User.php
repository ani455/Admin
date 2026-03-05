<?php

/**
 * User Model Class
 * Handles all user-related database operations
 */

class User {
    private $conn;
    private $table = 'nirvahaka_shonu';

    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Get user by ID
     */
    public function getById($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE ID = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting user: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user by email
     */
    public function getByEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE email_id = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting user by email: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all users with pagination
     */
    public function getAll($limit = 20, $offset = 0, $search = '') {
        try {
            $query = "SELECT * FROM " . $this->table;
            
            if (!empty($search)) {
                $search = "%{$search}%";
                $query .= " WHERE full_name LIKE ? OR email_id LIKE ? OR phone LIKE ?";
                $stmt = $this->conn->prepare($query . " LIMIT ? OFFSET ?");
                $stmt->bind_param("sssii", $search, $search, $search, $limit, $offset);
            } else {
                $stmt = $this->conn->prepare($query . " LIMIT ? OFFSET ?");
                $stmt->bind_param("ii", $limit, $offset);
            }
            
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count total users
     */
    public function count() {
        try {
            $result = $this->conn->query("SELECT COUNT(*) as total FROM " . $this->table);
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (Exception $e) {
            error_log("Error counting users: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Create new user
     */
    public function create($data) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO " . $this->table . 
                " (full_name, email_id, phone, password, role, status, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, NOW())"
            );

            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $role = $data['role'] ?? 'user';
            $status = $data['status'] ?? 'active';

            $stmt->bind_param(
                "ssssss",
                $data['full_name'],
                $data['email_id'],
                $data['phone'],
                $hashedPassword,
                $role,
                $status
            );

            if ($stmt->execute()) {
                return ['success' => true, 'id' => $this->conn->insert_id];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Update user
     */
    public function update($userId, $data) {
        try {
            $updateFields = [];
            $types = "";
            $params = [];

            if (isset($data['full_name'])) {
                $updateFields[] = "full_name = ?";
                $types .= "s";
                $params[] = $data['full_name'];
            }
            if (isset($data['email_id'])) {
                $updateFields[] = "email_id = ?";
                $types .= "s";
                $params[] = $data['email_id'];
            }
            if (isset($data['phone'])) {
                $updateFields[] = "phone = ?";
                $types .= "s";
                $params[] = $data['phone'];
            }
            if (isset($data['password'])) {
                $updateFields[] = "password = ?";
                $types .= "s";
                $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            if (isset($data['role'])) {
                $updateFields[] = "role = ?";
                $types .= "s";
                $params[] = $data['role'];
            }
            if (isset($data['status'])) {
                $updateFields[] = "status = ?";
                $types .= "s";
                $params[] = $data['status'];
            }

            if (empty($updateFields)) {
                return ['success' => false, 'error' => 'No fields to update'];
            }

            $updateFields[] = "updated_at = NOW()";
            $types .= "i";
            $params[] = $userId;

            $sql = "UPDATE " . $this->table . " SET " . implode(", ", $updateFields) . " WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                return ['success' => true, 'affected_rows' => $stmt->affected_rows];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Delete user
     */
    public function delete($userId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE ID = ?");
            $stmt->bind_param("i", $userId);
            
            if ($stmt->execute()) {
                return ['success' => true, 'affected_rows' => $stmt->affected_rows];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get user balance
     */
    public function getBalance($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT balance FROM " . $this->table . " WHERE ID = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result['balance'] ?? 0;
        } catch (Exception $e) {
            error_log("Error getting balance: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Update user balance
     */
    public function updateBalance($userId, $amount) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE " . $this->table . " SET balance = balance + ? WHERE ID = ?"
            );
            $stmt->bind_param("di", $amount, $userId);
            
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        } catch (Exception $e) {
            error_log("Error updating balance: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get user statistics
     */
    public function getStats($userId) {
        try {
            $stats = [];

            // Total bets
            $result = $this->conn->query(
                "SELECT COUNT(*) as total FROM bet_kacha WHERE nirvahaka_id = " . (int)$userId
            );
            $stats['total_bets'] = $result->fetch_assoc()['total'];

            // Total winnings
            $result = $this->conn->query(
                "SELECT SUM(amount) as total FROM transactions 
                 WHERE nirvahaka_id = " . (int)$userId . " AND type = 'win'"
            );
            $row = $result->fetch_assoc();
            $stats['total_winnings'] = $row['total'] ?? 0;

            // Last login
            $result = $this->conn->query(
                "SELECT last_login FROM " . $this->table . " WHERE ID = " . (int)$userId
            );
            $user = $result->fetch_assoc();
            $stats['last_login'] = $user['last_login'];

            return $stats;
        } catch (Exception $e) {
            error_log("Error getting user stats: " . $e->getMessage());
            return [];
        }
    }
}
