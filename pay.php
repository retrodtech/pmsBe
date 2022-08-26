<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');


$site = SERVER_INCLUDE_PATH;
include($site."/easebuzz-lib/easebuzz_payment_gateway.php");
include($site."/config.php");

    
    
    
    if(isset($_SESSION['OID'])){
        
        $boid = $_SESSION['OID'];
        
        $grossAmount = $_SESSION['gossCharge'];
        $userPay = $_SESSION['roomTotalPrice'];
        
        $checkRow = mysqli_fetch_assoc(mysqli_query($conDB, "select * from booking where id = '$boid'"));
        
        $bid = $checkRow['bookinId'];
            
            
            $easebuzzObj = new Easebuzz($MERCHANT_KEY, $SALT, $ENV);
    
            $name = $_POST['personName'];
            $email = $_POST['personEmail'];
            $phone = $_POST['personPhoneNo'];
            $amount =  $_SESSION['roomTotalPrice'];
            
            if (strpos($amount, '.') !== false) { 
                $amount = $amount;
            }else{
                $amount = $amount.'.00';
            }
           
           if(isset($_SESSION['pickUp'])){
               $grossAmount += $_SESSION['pickUp'];
           }
           
           $successUrl = WS_FRONT_SITE."/thank_you.php";
           $failedUrl = WS_FRONT_SITE."/failed.php";
            
            $postData = array ( 
                    "txnid" => "$bid", 
                    "amount" => "$amount", 
                    "firstname" => "$name", 
                    "email" => "$email", 
                    "phone" => "$phone", 
                    "productinfo" => "Room Book", 
                    "surl" => $successUrl, 
                    "furl" => $failedUrl, 
                    
            );
    
            $result = $easebuzzObj->initiatePaymentAPI($postData);    
           
          
            unset($_SESSION['roomTotalPrice']);
        
        
        
        
    }else{
            $site = WS_FRONT_SITE;
            echo "Some thing Wrong, Please Go to <a href='$site'>Home</a>";
        }
    
    

?>
