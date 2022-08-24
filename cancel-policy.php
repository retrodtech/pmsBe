<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$sql = mysqli_query($conDB, "select * from term where id ='2' ");

$row = mysqli_fetch_assoc($sql);

$title = $row['title'];
$termContent = $row['termContent'];
$policy =ucfirst($row['policy']);

if($termContent == ''){
    redirect('index.php');
}

?>

<!doctype html>
<html lang="en">

<head>
    <?php include(SERVER_BOOKING_PATH.'/screen/head.php') ?>
    <title><?php echo SITE_NAME ?> || <?php echo $policy ?> Policy</title>

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

<body>
    
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
    
    <?php include(SERVER_BOOKING_PATH.'/screen/navbar.php') ?>
    
    
    
    <section id="policySection">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="content">
                        <h2><?php echo $title ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <section id="policyContent">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="content">
                        <h4><?php echo $title ?></h4>
                        
                        <div calss="caption">
                            <?php echo $termContent ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <?php include(SERVER_BOOKING_PATH.'/screen/footer.php') ?>




    <?php include(SERVER_BOOKING_PATH.'/screen/script.php') ?>
    
    <script>
        
            $('.loadingbar').delay(500).animate({left: '0'}, 1500);
            $('.loadingBox').delay(500).animate({opacity: '1'}, 1000);
            $('#loadingScreen').delay(1500).animate({top: '-100%'}, 500);
            $('.loadingCircle').delay(4500).animate({opacity: '0'}, 500);
    </script>

    </body>

</html>