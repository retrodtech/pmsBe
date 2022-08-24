<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$amount = $_POST['net_amount_debit'];
$payId = $_POST['easepayid'];
$status = $_POST['status'];
$bookingId = $_POST['txnid'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="images/favicon.png" rel="shortcut icon" type="image/png">
    <title>Payment Failed</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.css'>
    <style>
        body{
          background-color: #f6f4f4;
          font-family: 'Raleway', sans-serif;
        }
        .teal{
          background-color: #ffc952 !important;
          color: #444444 !important;
        }
        a{
          color: #47b8e0 !important;
        }
        .message{
          text-align: left;
        }
        .price1{
        	font-size: 40px;
        	font-weight: 200;
        	display: block;
        	text-align: center;
        }
        .ui.message p {margin: 5px;}
        
        .ui.message p span{
            color:red;
        }
    </style>
</head>

<body>
    
    <div class="container">
      <div class="ui middle aligned center aligned grid">
        <div class="ui eight wide column">
       
          <form class="ui large form">
                    
              <div class="ui icon negative message">
                <i class="warning icon"></i>
                <div class="content">
                  <div class="header">
                    Oops! Something went wrong.
                  </div>
                  <p>Your Booing Id is <span><?php echo $bookingId ?></span> and Total Amount is <span><?php echo $amount ?></span> Payment Id Failed due to <span><?php echo $status ?></span>.</p>
                </div>
                
             </div>
          
              <a href="<?php echo FRONT_SITE ?>"> <span class="ui large teal submit fluid button">Try again</span></a>
          
          </form>
        </div>
      </div>
    </div>


</body>

</html>