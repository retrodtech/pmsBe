<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$ip = $_SERVER['REMOTE_ADDR'];
visiter_count($ip);


    
$current_date = strtotime(date('Y-m-d'));
$one_day = strtotime('1 day 00 second', 0);
$_SESSION['no_room'] = 1;
$_SESSION['no_guest'] = 2;
$_SESSION['night_stay'] = 1;
$_SESSION['checkIn'] = date('Y-m-d',$current_date);
$_SESSION['checkout'] = date('Y-m-d',$current_date + (1 * $one_day));


?>

<!doctype html>
<html lang="en">

<head>
 <?php include(SERVER_BOOKING_PATH.'/screen/head.php') ?>
    <title><?php echo SITE_NAME ?> || Quick Pay</title>

    <style>
        a.btn-action {
            background: #222;
            color: #fff;
            padding: 9px 13px;
            margin: 0 0 0 15px;
        }

        .carousel-inner img {
            width: 100%;
        }
        
        #loadingScreen {
            position: fixed;
            top: 0;
            left: 0;
            border: 0;
            right: 0;
            background: white;
            z-index: 105;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .loadingBox {
            width: 500px;
            margin: 0 auto;
            text-align: center;
            overflow: hidden;
            position: relative;
        }
        .loadingBox img {
            width: 150px;
            height: auto;
        }
        .loadingBox .loadingBarContainer {
            width: 100%;
            background: #eee;
            height: 4px;
            display: block;
            margin: 50px 0 0;
            overflow: hidden;
            border-radius: 5px;
        }
        .loadingBarContainer .loadingbar {
        	width: 100%;
        	height:4px;
        	background: #000;
        	position: absolute;
        	left: -100%;
        	border-radius: 5px;
        }
        .loadingCircle {
        	width: 75px;
        	height: 75px;
        	margin: 30px auto 0;
        	background: #fff;
        	display: block;
        	border-radius: 50%;
        	position: relative;
        	overflow: hidden;
        }
        .circleOuter {
        	width: 60px;
        	height: 60px;
        	background: #fff;
        	border-radius: 50%;
        	position: absolute;
        	left: 50%;
        	top: 50%;
        	transform: translate(-50%, -50%);
        	z-index: 2;
        }
        .circleLoader {
        	width: 75px;
        	height: 75px;
        	background: linear-gradient(to bottom, rgba(0,0,0,1) 0%,rgba(125,185,232,0) 100%);
        	position: absolute;
        	right: 50%;
        	bottom: 50%;
        	transform-origin: bottom right;
        	z-index: 1;
        	animation: rotateLoader 1.5s linear infinite;
        }
        @keyframes rotateLoader {
            from {transform: rotate(0deg);}
            to {transform: rotate(360deg);}
        }
        .btn-grad{
            color: #000 !important;
            cursor:pointer;
        }
        .btn-grad:hover{
            color:#fff !important;
        }

        .add_room_detail{
            display: flex;
            justify-content: flex-end;
        }
        #room_guest_select_form {
            position: relative;
            z-index: 10;
            background-color: #fff;
            max-width: 500px;
            width:100%;
            padding: 35px 20px;
            box-shadow: none;
            border-radius: 10px;
            border: 1px solid #b9b9b9;
            left: 0;
            top: 0;
            margin: 25px;
        }
        
    </style>

</head>
    
    <div id="loadingScreen">
        <div class="loadingBox">
        	<img src="<?php echo FRONT_SITE_IMG.hotelDetail()['logo'] ?>">
        	<div class="loadingBarContainer">
        		<div class="loadingbar"></div>
        	</div>
        
        	<div class="loadingCircle">
        		<div class="circleOuter"></div>
        		<div class="circleLoader"></div>
        	</div>
        </div>
    </div>
    
    <?php include(SERVER_BOOKING_PATH.'/screen/navbar.php'); require(SERVER_INCLUDE_PATH.'config.php');  ?>
    
    <div id="quickPaySection">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">                    
                    <div class="content">
                        <h1 class="paddtop1 font-weight lspace-sm">Pay Now - For Advance Booking</h1>
                        <img style="max-height:500px" src="admin/img/quickPayBg.png" alt="Quick Pay Image">
                    </div>
                </div>
                <div class="col-md-6">
                    <form action="<?php echo FRONT_BOOKING_SITE.'/quickpayment.php' ?>" method="POST" id="QuickPayForm">

                        <div class="group">
                            <div class="groupText">Personal Details</div>
                            <div class="form-group  mb-3">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter Your Name" class="form-control" required> 
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone">Phone</label>
                                <input type="number" id="phone" name="phone" placeholder="Enter Your Phone number" class="form-control" required> 
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="text" id="email" name="email" placeholder="Enter Your Email Id" class="form-control" required> 
                            </div>
                        </div>

                        <div class="group">
                            <div class="groupText">Room Details</div>
                            <div class="row">

                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label for="room">Room</label>
                                        <select name="room" id="room" class="form-control" required>
                                            <option value="0">Select Room</option>
                                            <?php
                                            
                                                foreach(getRoomArr(settingValue()['advancePay']) as $roomList){
                                                    $id = $roomList['id'];
                                                    $name = $roomList['name'];
                                                    echo '<option value="'.$id.'">'.$name.'</option>';
                                                }
                                            
                                            
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6 mb-3">
                                    <div class="form-group">
                                        <label for="nOfRoom">No Of Rooms</label>
                                        <select name="nOfRoom" id="nOfRoom" class="form-control">
                                            <option value="">Select Room</option>
                                            <?php
                                            
                                                for($i=1; $i<10; $i++){
                                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                                }
                                            
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="rtp">Check In</label>
                                                <input style="padding: 0.5rem .2rem;"  class="form-control" type="date" name="checkInDate" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="rtp">Check Out</label>
                                                <input style="padding: 0.5rem .2rem;"  class="form-control" type="date" name="checkOutDate" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3" id="amountSection">
                            <label for="qickPayNote">Request to hotel</label>
                            <textarea name="qickPayNote" id="qickPayNote" class="form-control"></textarea>
                        </div>

                        <div class="form-group mb-3" id="amountSection">
                            <label for="amount">Amount</label>
                            <input type="number" id="amount" name="amount" placeholder="Enter Amount" class="form-control" required> 
                        </div>

                        <button type="submit" class="btn btn-primary" id="qpSubmitBtn">Pay Now</button>


                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="verifyForm"></div>

    <?php include(SERVER_BOOKING_PATH.'/screen/footer.php') ?>

    


    <?php include(SERVER_BOOKING_PATH.'/screen/script.php') ?>

    </body>





    <script>
    
    
    $('.loadingbar').delay(500).animate({left: '0'}, 1500);
    $('.loadingBox').delay(500).animate({opacity: '1'}, 1000);
    $('#loadingScreen').delay(1500).animate({top: '-100%'}, 500);
    $('.loadingCircle').delay(4500).animate({opacity: '0'}, 500);
        
    
    
    $('#QuickPayForm').on('submit', function(e){
        var phone = $('#phone').val().trim();
        if (phone.length != 10) {
            e.preventDefault();
            alert('Invalid mobile No');
        }
    });

    

</script>

</html>