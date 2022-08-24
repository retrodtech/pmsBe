<?php

    include ('include/constant.php');
    include (SERVER_INCLUDE_PATH.'db.php');
    include (SERVER_INCLUDE_PATH.'function.php');

    $site = SERVER_INCLUDE_PATH;
    include($site."/easebuzz-lib/easebuzz_payment_gateway.php");
    include($site."/config.php");

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $room = $_POST['room'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];
    $qickPayNote = $_POST['qickPayNote'];
    $amount = $_POST['amount'];
    $nOfRoom = $_POST['nOfRoom'];
    
    $qpid = getQPBookingNumber();

    mysqli_query($conDB, "insert into quickpay(orderId,name,phone,email,nOfRoom,room,qickPayNote,amount,paymentStatus,checkIn,checkOut) values('$qpid','$name','$phone','$email','$nOfRoom','$room','$qickPayNote','$amount','pending','$checkInDate','$checkOutDate') ");

    $_SESSION['QPOID']=mysqli_insert_id($conDB);

    $easebuzzObj = new Easebuzz($MERCHANT_KEY, $SALT, $ENV);

    if (strpos($amount, '.') !== false) { 
        $amount = $amount;
    }else{
        $amount = $amount.'.00';
    }
    


    $successUrl = FRONT_SITE."/qp-thank.php";
    $failedUrl = FRONT_SITE."/qp-failed.php";

    $postData = array ( 
        "txnid" => "$qpid", 
        "amount" => "$amount", 
        "firstname" => "$name", 
        "email" => "$email", 
        "phone" => "$phone", 
        "productinfo" => "Quick Pay", 
        "surl" => $successUrl, 
        "furl" => $failedUrl, 
    );

    $result = $easebuzzObj->initiatePaymentAPI($postData);    
  



?>