<?php
/**
 * Main Dashboard Entry Point
 * Routing and page management for admin panel
 */

// Include configuration
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Include utilities
require_once __DIR__ . '/app/utils/Security.php';
require_once __DIR__ . '/app/utils/Formatter.php';
require_once __DIR__ . '/app/utils/Functions.php';

// Include models
require_once __DIR__ . '/app/models/Database.php';
require_once __DIR__ . '/app/models/Auth.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Transaction.php';
require_once __DIR__ . '/app/models/Game.php';

// Check authentication
Auth::requireLogin();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth = new Auth($conn);
    $auth->logout();
    header('Location: index.php');
    exit;
}

// Get flash message if any
$flash = getFlash();

// Initialize models
$authModel = new Auth($conn);
$userModel = new User($conn);
$transactionModel = new Transaction($conn);
$gameModel = new Game($conn);

// Get current page
$page = $_GET['page'] ?? 'dashboard';
$page = preg_replace('/[^a-z_]/', '', $page); // Sanitize

// Set page title
$pageTitle = ucfirst(str_replace('_', ' ', $page));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0F172A">
    <title><?php echo $pageTitle; ?> - Admin Panel</title>
    <link rel="stylesheet" href="app/assets/css/main.css">
</head>
<body>
    <div class="main-layout">
        <!-- Sidebar Navigation -->
        <?php include __DIR__ . '/app/layouts/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <?php include __DIR__ . '/app/layouts/header.php'; ?>
            
            <!-- Page Content -->
            <div class="page-container">
          <div class="row">
            <div class="col-sm-12 mb-4 mb-xl-0">
              <h4 class="font-weight-bold text-dark">Hi, welcome back!</h4>
              <p class="font-weight-normal mb-2 text-muted"><?php echo date("F d, Y"); ?></p>
            </div>
          </div>
		  <?php 
			$chkserial = mysqli_query($conn,"select * from `nirvahaka_shonu` where `unohs`='".$_SESSION['unohs']."'");
			$salu = mysqli_fetch_array($chkserial);
			$dashboard = $salu['dashboard'];
			if($dashboard == 1){
		  ?>
		  <div class="row">
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Today User Join</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT count(*) as 'total_user' FROM shonu_subjects where status = 1 AND id NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(createdate) = DATE('".$curdate."')");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$total_user = $row["total_user"];
							echo $total_user;                  																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Today's Recharge</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT sum(motta) as 'pending' FROM thevani WHERE sthiti = '1' AND balakedara NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(dinankavannuracisi) = DATE('".$curdate."')");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$pending = $row["pending"];
							echo number_format($pending,0);                  																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Today's Withdrawal</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT sum(motta) as 'succ_w' FROM hintegedukolli where sthiti = 1 AND balakedara NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(dinankavannuracisi) = DATE('".$curdate."')");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$succ_w = $row["succ_w"];
							echo number_format($succ_w,0);                  																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">User Balance</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT sum(motta) as 'wallt' FROM shonu_kaichila where balakedara NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND motta > 0");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$zero_bl = $row["wallt"];
							echo number_format($zero_bl,2);                  																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
				<a href="manage_user.php">
                  <div class="panel-footer">
                    <span class="pull-left" style="color: white;">See in Detail</span>
                    <div class="clearfix"></div>
                  </div>
                </a>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Total Users</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT count(*) as 'total_user' FROM shonu_subjects where id NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND status = 1");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$total_user = $row["total_user"];
							echo $total_user;                  																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
				<a href="manage_user.php">
                  <div class="panel-footer">
                    <span class="pull-left" style="color: white;">See in Detail</span>
                    <div class="clearfix"></div>
                  </div>
                </a>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Pending Recharge</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT sum(motta) as '2' FROM thevani where balakedara NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND sthiti = '0'");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$pending = $row["2"];
							echo number_format($pending);                 																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
				<a href="deposit_update.php">
                  <div class="panel-footer">
                    <span class="pull-left" style="color: white;">See in Detail</span>
                    <div class="clearfix"></div>
                  </div>
                </a>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Success Recharge</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT sum(motta) as 'pending' FROM thevani where balakedara NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND sthiti = '1'");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$pending = $row["pending"];
							echo number_format($pending,0);                																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
				<a href="deposit_update.php">
                  <div class="panel-footer">
                    <span class="pull-left" style="color: white;">See in Detail</span>
                    <div class="clearfix"></div>
                  </div>
                </a>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Total Withdrawal</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT sum(motta) as 'pending_w' FROM hintegedukolli where balakedara NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND sthiti = '1'");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$pending_w = $row["pending_w"];
							echo number_format($pending_w);                 																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
				<a href="withdraw_accept_list.php">
                  <div class="panel-footer">
                    <span class="pull-left" style="color: white;">See in Detail</span>
                    <div class="clearfix"></div>
                  </div>
                </a>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Withdrawal Requests</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						  $result = mysqli_query($conn,"SELECT sum(motta) as 'approve_withdrawal' FROM hintegedukolli where balakedara NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND sthiti = '0'");
					?> 
					<?php
						  if (mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_array($result);							                            							
							$approve_withdrawal = $row["approve_withdrawal"];
							echo number_format($approve_withdrawal);                 																  
						  }
						  else 
						  {
							echo "0";
						  }
					?>
					</h4>
                </div>
				<a href="manage_withdraw.php">
                  <div class="panel-footer">
                    <span class="pull-left" style="color: white;">See in Detail</span>
                    <div class="clearfix"></div>
                  </div>
                </a>
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Today's total bet</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						    $bet_wingo_1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_wingo_3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_drei` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_wingo_5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_funf` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_wingo_10 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_zehn` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_kemuru` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_kemuru_drei` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_kemuru_funf` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_10 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_kemuru_zehn` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_aidudi` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_aidudi_drei` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_aidudi_funf` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_10 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(ketebida) as total FROM `bajikattuttate_aidudi_zehn` where byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$total_bet = $bet_wingo_1['total'] + $bet_wingo_3['total'] + $bet_wingo_5['total'] + $bet_wingo_10['total'] + $bet_k3_1['total'] + $bet_k3_3['total'] + $bet_k3_5['total'] + $bet_k3_10['total'] + $bet_5d_1['total'] + $bet_5d_3['total'] + $bet_5d_5['total'] + $bet_5d_10['total'];
					?> 
					<?php
							$asila = $total_bet;
						  echo number_format($total_bet, 2);
					?>
					</h4>
                </div>				
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Today's total win</p>
					<h4 class="text-amount" style="text-align:left">
					<?php
						    $bet_wingo_1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_wingo_3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_drei` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_wingo_5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_funf` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_wingo_10 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_zehn` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_kemuru` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_kemuru_drei` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_kemuru_funf` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_k3_10 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_kemuru_zehn` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_aidudi` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_3 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_aidudi_drei` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_5 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_aidudi_funf` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$bet_5d_10 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sum(sesabida) as total FROM `bajikattuttate_aidudi_zehn` where `phalaphala` = 'gagner' AND byabaharkarta NOT IN (SELECT balakedara FROM `demo` WHERE `sthiti`='1') AND DATE(tiarikala) = DATE('".$curdate."')"));
							$total_bet = $bet_wingo_1['total'] + $bet_wingo_3['total'] + $bet_wingo_5['total'] + $bet_wingo_10['total'] + $bet_k3_1['total'] + $bet_k3_3['total'] + $bet_k3_5['total'] + $bet_k3_10['total'] + $bet_5d_1['total'] + $bet_5d_3['total'] + $bet_5d_5['total'] + $bet_5d_10['total'];
					?> 
					<?php
							$gala = $total_bet;
						  echo number_format($total_bet, 2);
					?>
					</h4>
                </div>				
            </div>
			<div style="background-color: #3362ff; color: white; padding: 20px; margin: 10px;"
              class="dashboard_box blue_bg col-md-3 border-radius">
                <div class="title">
                  <p class="text-title" style="text-align:left">Today's profit</p>
					<h4 class="text-amount" style="text-align:left">					
					<?php
							$amount = $asila - $gala;
							echo round($amount,2);
					?>
					</h4>
                </div>				
            </div>

		  </div>
		  <?php } ?>
						  
			  <?php
// Fetch current settings from game_win_setting
$sql = "SELECT game, process_type FROM game_win_settings LIMIT 1";
$result = $conn->query($sql);

$game_mode = "wingo"; // Default value
$process_type = "highest_bet_wins"; // Default value

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $game_mode = $row['game'];
    $process_type = $row['process_type'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_mode = $_POST['game_mode'];
    $process_type = $_POST['process_type'];

    // Update game_win_setting in the database
    $update_query = "UPDATE game_win_settings SET game = '$game_mode', process_type = '$process_type' WHERE id = 1";
    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Settings updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating settings: " . $conn->error . "');</script>";
    }
}

                <!-- Dynamic Page Content -->
                <?php
                // Route to appropriate page
                $pageFile = __DIR__ . '/app/views/' . $page . '.php';
                
                if (file_exists($pageFile)) {
                    include $pageFile;
                } else {
                    // Default to dashboard if page not found
                    include __DIR__ . '/app/views/dashboard.php';
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="app/assets/js/main.js"></script>
</body>
</html>
