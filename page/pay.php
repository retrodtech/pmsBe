<?php
    include ('../include/constant.php');
    include (SERVER_INCLUDE_PATH.'db.php');
    include (SERVER_INCLUDE_PATH.'function.php');
    include (SERVER_INCLUDE_PATH.'config.php');
   

    $site = SERVER_INCLUDE_PATH;

    include($site."/easebuzz-lib/easebuzz_payment_gateway.php");
    include($site."/easebuzz-lib/payment.php");
    include($site."/config.php");

    
        $name = $_POST['personName'];
        $email = $_POST['personEmail'];
        $phone = $_POST['personPhoneNo'];
        $slug = $_POST['slug'];
        $amount =  $_SESSION['roomTotalPrice'];
        
        $easebuzzObj = new Easebuzz($MERCHANT_KEY, $SALT, $ENV);

        if (strpos($amount, '.') !== false) { 
            $amount = $amount;
        }else{
            $amount = $amount.'.00';
        }
       
       if(isset($_SESSION['pickUp'])){
           $grossAmount += $_SESSION['pickUp'];
       }
       
        $txnid = $_SESSION['bookingId'];

        // mysqli_query($con, "insert into payment(textId,name,email,phone,amount,status,paymentId) values('$txnid','$name','$email','$phone','$amount','pending','')");

        $hash = str_openssl_enc("name=$slug&bid=$txnid");

        $postData = array ( 
            "txnid" => $txnid, 
            "amount" => $amount, 
            "firstname" => $name, 
            "email" => $email, 
            "phone" => $phone, 
            "productinfo" => "For BE", 
            "udf1" => $slug, 
            "surl" => WS_FRONT_SITE."/thank_you.php?id=".$hash, 
            "furl" => WS_FRONT_SITE."/failed.php?id=".$hash
        );
    
        $data = _payment($postData, false, $MERCHANT_KEY, $SALT, $ENV);    
        

        echo json_encode($data);

?>          