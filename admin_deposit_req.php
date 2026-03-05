<?php 
include("conn.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['app'])){
    
    $uid = $_POST['uid'];
    $deposit = $_POST['amount'];
    $depositdup = $deposit;
    $sid = $_POST['sid'];
    $ref_num = $_POST['ref_num'];
    
    date_default_timezone_set('Asia/Karachi');
    $today = date("F j, Y, g:i a"); 
    $tday = date("Y-m-d H:i");
    
    $dt = $_POST['date'];//added
    $timestamp = strtotime($dt);
    $dt2 = date("Y-m-d H:i", $timestamp);
    
    // Debug: Check if values are received properly
    error_log("Approve Request - UID: $uid, Amount: $deposit, SID: $sid, Ref: $ref_num");
    
    //Find referer
    $self = mysqli_query($conn , "SELECT code FROM shonu_subjects WHERE id = '".$uid."'");
    if(mysqli_num_rows($self) > 0) {
        $selfArray = mysqli_fetch_array($self);
        $ref = mysqli_query($conn , "SELECT id FROM shonu_subjects WHERE owncode = '".$selfArray['code']."'");
        if(mysqli_num_rows($ref) > 0) {
            $refArray = mysqli_fetch_array($ref);
            $refid = $refArray['id'];
        } else {
            $refid = 0;
        }
    } else {
        $refid = 0;
    }
    
    // Referral bonus calculation (currently all 0)
    if($depositdup >= '500' && $depositdup < '1000'){ $refbn = 0; }
    else if($depositdup >= '1000' && $depositdup < '3000'){ $refbn = 0; }
    else if($depositdup >= '3000' && $depositdup < '4000'){ $refbn = 0; }
    else if($depositdup >= '4000' && $depositdup < '5000'){ $refbn = 0; }
    else if($depositdup >= '5000' && $depositdup < '10000'){ $refbn = 0; }
    else if($depositdup >= '10000' && $depositdup < '50000'){ $refbn = 0; }
    else if($depositdup >= '50000' && $depositdup < '100000'){ $refbn = 0; }
    else if($depositdup >= '100000'){ $refbn = 0; }
    else{ $refbn = 0; }
    
    $refbnthr = 0;
    
    // Get referrer's current wallet
    if($refid > 0) {
        $refwal = mysqli_query($conn , "SELECT motta FROM shonu_kaichila WHERE balakedara = '".$refid."'");
        if(mysqli_num_rows($refwal) > 0) {
            $refwalA = mysqli_fetch_array($refwal);
            $refwalB = $refwalA['motta'];
            $refwalF = intval($refwalB) + intval($refbn);
            $refwalFthr = intval($refwalB) + intval($refbnthr);
        } else {
            $refwalF = intval($refbn);
            $refwalFthr = intval($refbnthr);
        }
    }
    
    //Find 1st Rech (First deposit check)
    $up2 = mysqli_query($conn , "SELECT shonu FROM thevani WHERE balakedara = '".$uid."' AND sthiti = '1'");
    $up2row = mysqli_num_rows($up2);
    error_log("Previous approved deposits count: $up2row");
    
    //Bonus calculation (currently disabled - all 0)
    $rechqueryaa = mysqli_query($conn, "SELECT rechargebonus FROM parametredepaiement");
    if(mysqli_num_rows($rechqueryaa) > 0) {
        $rechqueryarrayaa = mysqli_fetch_array($rechqueryaa);
        $rechargebonus = $rechqueryarrayaa['rechargebonus'];
    } else {
        $rechargebonus = 0;
    }
    $bn = '0';
    if($up2row == 0){
        //$bn = ($rechargebonus * $depositdup) / 100;
        $bn = '0';
    } else {
        $bn = '0';
    }
    
    // Get user's current wallet
    $up = mysqli_query($conn , "SELECT motta FROM shonu_kaichila WHERE balakedara = '".$uid."'");
    if(mysqli_num_rows($up) > 0) {
        $rup = mysqli_fetch_array($up);
        $current_balance = intval($rup['motta']);
        error_log("Current user balance: $current_balance");
    } else {
        // If user doesn't have wallet entry, create one
        $current_balance = 0;
        $create_wallet = mysqli_query($conn, "INSERT INTO shonu_kaichila (balakedara, motta) VALUES ('$uid', '0')");
        error_log("Created new wallet for user: $uid");
    }
    
    // Calculate new balance
    $addmoney = intval($current_balance) + intval($deposit) + intval($bn);
    error_log("New balance calculation: $current_balance + $deposit + $bn = $addmoney");
    
    // Update user's wallet
    $wal = mysqli_query($conn, "UPDATE shonu_kaichila SET motta = '".$addmoney."' WHERE balakedara = '".$uid."'");
    
    if($wal){
        // Update deposit status to approved
        $succes = mysqli_query($conn, "UPDATE thevani SET sthiti = '1' WHERE balakedara = '".$uid."' AND motta = '$deposit' AND dinankavannuracisi = '".$dt."' AND shonu = '".$sid."'");
        
        if($succes) {
            error_log("Deposit approved successfully for SID: $sid");
            
            // Update referral wallet if applicable
            if($refid > 0) {
                if($up2row == 0){
                    $refwll = mysqli_query($conn, "UPDATE shonu_kaichila SET motta = '".$refwalF."' WHERE balakedara = '".$refid."'");
                    error_log("Updated referrer (first deposit): $refid with amount: $refwalF");
                }
                else if($up2row == 2){ // Note: This condition seems specific
                    $refwll = mysqli_query($conn, "UPDATE shonu_kaichila SET motta = '".$refwalFthr."' WHERE balakedara = '".$refid."'");
                    error_log("Updated referrer (other deposit): $refid with amount: $refwalFthr");
                }
            }
            
            echo "1~".$sid;
        } else {
            error_log("Failed to update thevani table: " . mysqli_error($conn));
            echo "2~Database update failed";
        }
    } else {
        error_log("Failed to update wallet: " . mysqli_error($conn));
        echo "2~Wallet update failed"; 
    }
}

if(isset($_POST['rej'])){
    
    $sid = $_POST['sid'];
    $ref_num = $_POST['ref_num'];
    $dt = $_POST['date'];
    $timestamp = strtotime($dt);
    $dt2 = date("Y-m-d H:i", $timestamp);
    
    date_default_timezone_set('Asia/Karachi');
    $tdayy = date("Y-m-d H:i");
    
    $uid = $_POST['uid'];
    $deposit = $_POST['amount'];
    
    error_log("Reject Request - UID: $uid, Amount: $deposit, SID: $sid");
    
    $reject = mysqli_query($conn, "UPDATE thevani SET sthiti = '2' WHERE balakedara = '".$uid."' AND motta = '$deposit' AND dinankavannuracisi = '".$dt."' AND shonu = '".$sid."'");
    
    if($reject){
        error_log("Deposit rejected successfully for SID: $sid");
        echo "1~".$sid;
    } else {
        error_log("Failed to reject deposit: " . mysqli_error($conn));
        echo "2~Reject failed";
    }
}
?>