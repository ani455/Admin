<?php
session_start();
if(empty($_SESSION['unohs'])) {
    header("Location: index.php?msg=unauthorized");
    exit();
}
include("conn.php");
$bonusTypes = [
    3   => "Red envelope",
    8   => "Agent red envelope recharge",
    10  => "Recharge gift",
    13  => "Bonus recharge",
    14  => "First full gift",
    20  => "Invite bonus",
    25  => "Card binding gift",
    107 => "Weekly Awards",
    124 => "Agent Bonus",
    118 => "Daily Awards",
    117 => "New members get bonuses by playing games",
    115 => "Return Awards",
];

// Function to add bonus
function addBonus($userId, $type, $amount, $remark, $conn, $bonusTypes) {
    $tableNames = [
        3   => "hodike_balakedara",
        8   => "agent_red_envelope_recharge_table",
        10  => "recharge_gift_table",
        13  => "bonus_recharge_table",
        14  => "first_full_gift_table",
        20  => "invite_bonus_table",
        25  => "card_binding_gift_table",
        107 => "weekly_awards_table",
        124 => "agent_bonus_table",
        118 => "daily_awards_table",
        117 => "new_members_bonus_table",
        115 => "return_awards_table",
    ];

    if (!isset($tableNames[$type])) {
        return "Invalid bonus type.";
    }

    $tableName = $tableNames[$type];
    $date = date("Y-m-d H:i:s");
    $serial = "Imitator";

    // Insert bonus record into the relevant table
    $stmt = $conn->prepare("INSERT INTO $tableName (userkani, price, serial, shonu, remark) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        return "Error preparing statement: " . $conn->error;
    }

    if (!$stmt->bind_param("idsss", $userId, $amount, $serial, $date, $remark)) {
        return "Error binding parameters: " . $stmt->error;
    }

    if ($stmt->execute()) {
        // Now update the user's balance in the `shonu_kaichila` table
        $stmt->close();

        // Assuming the `shonu_kaichila` table has the user's balance, identified by `balakedara` as the user ID
        $updateStmt = $conn->prepare("UPDATE shonu_kaichila SET motta = ROUND(motta + ?, 2) WHERE balakedara = ?");
        if (!$updateStmt) {
            return "Error preparing update statement: " . $conn->error;
        }

        if (!$updateStmt->bind_param("di", $amount, $userId)) {
            return "Error binding parameters for update: " . $updateStmt->error;
        }

        if ($updateStmt->execute()) {
            $updateStmt->close();
            return "Bonus added and balance updated successfully!";
        } else {
            $updateStmt->close();
            return "Error updating balance: " . $updateStmt->error;
        }
    } else {
        $stmt->close();
        return "Error executing statement: " . $stmt->error;
    }
}

// Input handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);
    $type = intval($_POST['type']);
    $amount = floatval($_POST['amount']);
    $remark = htmlspecialchars($_POST['remark'] ?? '');

    if ($userId > 0 && $type > 0 && $amount > 0) {
        $result = addBonus($userId, $type, $amount, $remark, $conn, $bonusTypes);
        $_SESSION['msg'] = $result;
    } else {
        $_SESSION['error'] = "Please provide valid inputs for all required fields: user_id, type, and amount.";
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Bonus Management</title>
    <base href="/">
    <link rel="stylesheet" href="/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/vendors/feather/feather.css">
    <link rel="stylesheet" href="/vendors/base/vendor.bundle.base.css">
    <link rel="stylesheet" href="/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendors/jquery-bar-rating/fontawesome-stars-o.css">
    <link rel="stylesheet" href="/vendors/jquery-bar-rating/fontawesome-stars.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" href="/images/favicon.png">
    <style>
        .bonus-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 700px;
        }
        
        .bonus-title {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
        }
        
        .bonus-form .form-group {
            margin-bottom: 1.8rem;
        }
        
        .bonus-form label {
            display: block;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }
        
        .bonus-input {
            width: 100%;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .bonus-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,0.2);
            outline: none;
        }
        
        .bonus-btn {
            background: #007bff;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        
        .bonus-btn:hover {
            background: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.25);
        }

        .alert-container {
            max-width: 700px;
            margin: 20px auto;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
         <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="dashboard.php"><img src="/images/logo.png" alt="logo"></a>
                <a class="navbar-brand brand-logo-mini" href="dashboard.php"><img src="/images/logo-mini.png" alt="logo"></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown d-flex mr-4">
                        <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="icon-cog"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <a class="dropdown-item preview-item" href="logout.php">
                                <i class="icon-inbox"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <div class="user-profile">
                    <div class="user-image">
                        <img src="/images/faces/face28.png">
                    </div>
                    <div class="user-name">ALADDINN GAME</div>
                    <div class="user-designation">Admin</div>
                </div>
                <?php include 'compass.php'; ?>
            </nav>

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="alert-container">
                        <?php if(isset($_SESSION['msg'])): ?>
                            <div class="alert alert-success">
                                <?= htmlspecialchars($_SESSION['msg']) ?>
                                <?php unset($_SESSION['msg']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="bonus-container">
                        <h2 class="bonus-title">Assign User Bonus</h2>
                        <form class="bonus-form" method="POST" id="bonusForm">
                            <div class="form-group">
                                <label for="user_id">User ID</label>
                                <input type="number" 
                                       class="bonus-input" 
                                       name="user_id" 
                                       id="user_id" 
                                       required
                                       placeholder="Enter User ID">
                            </div>

                            <div class="form-group">
                                <label for="type">Bonus Type</label>
                                <select class="bonus-input" name="type" id="type" required>
                                    <?php foreach ($bonusTypes as $typeId => $typeName): ?>
                                        <option value="<?= $typeId ?>"><?= htmlspecialchars($typeName) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount">Bonus Amount</label>
                                <input type="number" 
                                       step="0.01" 
                                       class="bonus-input" 
                                       name="amount" 
                                       id="amount" 
                                       required
                                       placeholder="Enter Amount">
                            </div>

                            <div class="form-group">
                                <label for="remark">Remark (Optional)</label>
                                <textarea class="bonus-input" 
                                          name="remark" 
                                          id="remark" 
                                          rows="3"
                                          placeholder="Add any remarks..."></textarea>
                            </div>

                            <button type="submit" class="bonus-btn">
                                <i class="mdi mdi-gift-outline mr-2"></i>
                                Assign Bonus
                            </button>
                        </form>
                    </div>
                </div>
                <footer class="footer">
                    <!-- Footer remains unchanged -->
                </footer>
            </div>
        </div>
    </div>

    <!-- Original scripts kept -->
    <script src="/vendors/base/vendor.bundle.base.js"></script>
    <script src="/js/off-canvas.js"></script>
    <script src="/js/hoverable-collapse.js"></script>
    <script src="/js/template.js"></script>

    <!-- Added form handling scripts -->
    <script>
        // Reset form after submission
        document.addEventListener('DOMContentLoaded', function() {
            // Clear form inputs
            document.getElementById('bonusForm').reset();

            // Fade out alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // Prevent form resubmission
            if(window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });
    </script>
</body>
</html>
