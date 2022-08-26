<?php

    include ('include/constant.php');
    include (SERVER_INCLUDE_PATH.'db.php');
    include (SERVER_INCLUDE_PATH.'function.php');

    $pid = $_POST['pid'];
    $txnid = $_POST['txnid'];
    $sql = "update booking set payment_status='1', payment_id='$pid' where bookinId = '$txnid'";

    if(mysqli_query($conDB, $sql)){
        echo 1;
    }

?>     