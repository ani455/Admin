<?php
session_start();
if(empty($_SESSION['unohs'])) {
    header("Location: index.php?msg=unauthorized");
    exit();
}
include("conn.php");

// Delete Admin
if(isset($_POST['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);
    $query = "DELETE FROM nirvahaka_shonu WHERE unohs = '$delete_id'";
    if(mysqli_query($conn, $query)) {
        $_SESSION['msg'] = "Admin deleted successfully";
    } else {
        $_SESSION['error'] = "Delete failed: " . mysqli_error($conn);
    }
    header("Location: addadmin.php");
    exit();
}

// Add/Update Admin
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['serial'])) {
    $serial = mysqli_real_escape_string($conn, $_POST['serial']);
    $maxusers = mysqli_real_escape_string($conn, $_POST['maxusers']);
    $dashboard = isset($_POST['dashboard']) ? 1 : 0;
    $wingomanager = isset($_POST['wingomanager']) ? 1 : 0;
    $k3manager = isset($_POST['k3manager']) ? 1 : 0;
    $d5manager = isset($_POST['d5manager']) ? 1 : 0;
    $finance = isset($_POST['finance']) ? 1 : 0;
    $managegame = isset($_POST['managegame']) ? 1 : 0;
    $status = 1;

    if(isset($_POST['update_id'])) { // Update
        $update_id = mysqli_real_escape_string($conn, $_POST['update_id']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        
        $password_update = "";
        if(!empty($maxusers)) {
            $password_update = ", guptapada = '".md5($maxusers)."'";
        }

        $sql = "UPDATE nirvahaka_shonu SET 
                dashboard = '$dashboard',
                wingomanager = '$wingomanager',
                k3manager = '$k3manager',
                `5dmanager` = '$d5manager',
                finance = '$finance',
                managegame = '$managegame',
                sthiti = '$status'
                $password_update
                WHERE unohs = '$update_id'";

        if(mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Admin updated successfully";
        } else {
            $_SESSION['error'] = "Update failed: " . mysqli_error($conn);
        }
    } else { // Add
        $check = mysqli_query($conn, "SELECT * FROM nirvahaka_shonu WHERE nirvahaka_hesaru = '$serial'");
        if(mysqli_num_rows($check) > 0) {
            $_SESSION['error'] = "Username already exists";
        } else {
            $sql = "INSERT INTO nirvahaka_shonu 
                    (hesaru, nirvahaka_hesaru, guptapada, sthiti, dashboard, wingomanager, k3manager, `5dmanager`, finance, managegame) 
                    VALUES 
                    ('$serial', '$serial', '".md5($maxusers)."', '$status', '$dashboard', '$wingomanager', '$k3manager', '$d5manager', '$finance', '$managegame')";
            
            if(mysqli_query($conn, $sql)) {
                $_SESSION['msg'] = "Admin added successfully";
            } else {
                $_SESSION['error'] = "Add failed: " . mysqli_error($conn);
            }
        }
    }
    header("Location: addadmin.php");
    exit();
}

// Fetch for editing
$edit_data = null;
if(isset($_GET['edit_id'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit_id']);
    $result = mysqli_query($conn, "SELECT * FROM nirvahaka_shonu WHERE unohs = '$edit_id'");
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Management</title>
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
        .cool-input {
            border: 2px solid #007bff;
            border-radius: 0.25rem;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .cool-input:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .cool-button {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }
        .cool-button:hover {
            background-color: #0056b3;
            color: #fff;
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
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
                    <li class="nav-item dropdown d-flex mr-4 ">
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
                    <?php if(isset($_SESSION['msg'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-sm-12 mb-4 mb-xl-0">
                            <h4 class="font-weight-bold text-dark"><?= $edit_data ? 'Edit Admin' : 'Add Admin'; ?></h4>
                        </div>
                    </div>

                    <div class="row">
                        <form method="POST" autocomplete="off">
                            <?php if($edit_data): ?>
                                <input type="hidden" name="update_id" value="<?= $edit_data['unohs'] ?>">
                            <?php endif; ?>
                            
                            <input name="serial" type="text" placeholder="Username" required 
                                   value="<?= $edit_data['nirvahaka_hesaru'] ?? '' ?>" 
                                   class="cool-input" <?= $edit_data ? 'readonly' : '' ?>>
                            <br><br>
                            
                            <input name="maxusers" type="password" placeholder="Password" 
                                   class="cool-input" <?= !$edit_data ? 'required' : '' ?>>
                            <?php if($edit_data): ?><small>(Leave blank to keep current password)</small><?php endif; ?>
                            <br><br>

                            <div class="d-flex flex-wrap gap-4">
                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="dashboard" value="1" <?= ($edit_data['dashboard'] ?? 0) ? 'checked' : '' ?>> Dashboard
                                </label>
                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="wingomanager" value="1" <?= ($edit_data['wingomanager'] ?? 0) ? 'checked' : '' ?>> Wingo Manager
                                </label>
                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="k3manager" value="1" <?= ($edit_data['k3manager'] ?? 0) ? 'checked' : '' ?>> K3 Manager
                                </label>
                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="d5manager" value="1" <?= ($edit_data['5dmanager'] ?? 0) ? 'checked' : '' ?>> 5D Manager
                                </label>
                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="finance" value="1" <?= ($edit_data['finance'] ?? 0) ? 'checked' : '' ?>> Finance
                                </label>
                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="managegame" value="1" <?= ($edit_data['managegame'] ?? 0) ? 'checked' : '' ?>> Manage Game
                                </label>
                            </div>
                            <br>

                            <?php if($edit_data): ?>
                                <div class="d-flex align-items-center gap-3">
                                    <label>Status:</label>
                                    <select name="status" class="cool-input">
                                        <option value="1" <?= ($edit_data['sthiti'] == 1) ? 'selected' : '' ?>>Active</option>
                                        <option value="0" <?= ($edit_data['sthiti'] == 0) ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                </div>
                                <br>
                            <?php endif; ?>

                            <button type="submit" class="cool-button btn-primary">
                                <?= $edit_data ? 'Update Admin' : 'Add Admin' ?>
                            </button>
                            <?php if($edit_data): ?>
                                <a href="addadmin.php" class="cool-button btn-secondary">Cancel</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Admin List</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Dashboard</th>
                                                    <th>Wingo</th>
                                                    <th>K3</th>
                                                    <th>5D</th>
                                                    <th>Finance</th>
                                                    <th>Game</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $result = mysqli_query($conn, "SELECT * FROM nirvahaka_shonu");
                                                while($row = mysqli_fetch_assoc($result)):
                                                ?>
                                                <tr>
                                                    <td><?= $row['nirvahaka_hesaru'] ?></td>
                                                    <td><?= $row['dashboard'] ? '✓' : '✗' ?></td>
                                                    <td><?= $row['wingomanager'] ? '✓' : '✗' ?></td>
                                                    <td><?= $row['k3manager'] ? '✓' : '✗' ?></td>
                                                    <td><?= $row['5dmanager'] ? '✓' : '✗' ?></td>
                                                    <td><?= $row['finance'] ? '✓' : '✗' ?></td>
                                                    <td><?= $row['managegame'] ? '✓' : '✗' ?></td>
                                                    <td><span class="badge <?= $row['sthiti'] ? 'badge-success' : 'badge-danger' ?>">
                                                        <?= $row['sthiti'] ? 'Active' : 'Inactive' ?>
                                                    </span></td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="addadmin.php?edit_id=<?= $row['unohs'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                                            <form method="POST" action="addadmin.php">
                                                                <input type="hidden" name="delete_id" value="<?= $row['unohs'] ?>">
                                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025. All rights reserved.</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="/vendors/base/vendor.bundle.base.js"></script>
    <script src="/js/off-canvas.js"></script>
    <script src="/js/hoverable-collapse.js"></script>
    <script src="/js/template.js"></script>
    <script>
        if(window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        <?php if(!isset($_GET['edit_id'])): ?>
            document.querySelector("form").reset();
        <?php endif; ?>
    </script>
</body>
</html>