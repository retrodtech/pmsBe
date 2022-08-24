<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'add_to_package.php');
$obj = new add_to_package();


$id = $_GET['id'];

if(isset($_GET['id'])){
    $sql = mysqli_query($conDB, "select * from package where slug = '$id' and status = '1'");

    if(mysqli_num_rows($sql)> 0){
        $row = mysqli_fetch_assoc($sql);
        $name = $row['name'];
        $id = $row['id'];
        $pkimg = FRONT_SITE_IMG.'package/'.$row['img'];
        $duration = $row['duration'];
        $description = $row['description'];
        $room = $row['room'];
        $rdid = $row['rdid'];
        $discount = $row['discount'];
        $car = $row['car'];
        $pickup = $row['pickup'];
        $carPrice = getCarPriceById($car);
        $noAdult = getRoomAdultCountById($room);
        $noChild = getRoomChildCountById($room);
        $checkIn = date('Y-m-d');
        
        $obj->addPackage($id,$room,$rdid,$car,$noAdult,$noChild,$checkIn,$duration);
        
        $roomPrice = getRoomLowPriceById($room,$_SESSION['checkIn']);
        
        if($pickup == 'Yes'){
            $pickupPrice = settingValue()['pckupDropPrice'];
        }else{
            $pickupPrice = 0;
        }
        
        $obj-> updatePickUp($pickupPrice);
        
        $carName = getCarDetailById($car)[0]['name'];
        $carImg = FRONT_SITE_IMG.'car/'.getCarDetailById($car)[0]['img']; 

        $roomImg = FRONT_SITE_ROOM_IMG.getImageById($room)[0];
        $roomType = getRoomTypeById($room);
        $roomName = getRoomNameById($room);
        $ratePlan = getRatePlanByRoomDetailId($rdid);
     
        $roomOrginalPrice = getRoomPriceById($rdid,$_SESSION['package']['checkIn']);;

        

        

        
    }else{
        redirect('index.php');
        die();
    }
}else{
    redirect('index.php');
    die();
}


?>

<!doctype html>
<html lang="en">

    <head>
        <?php include(SERVER_BOOKING_PATH.'/screen/head.php') ?>
        <title><?php echo $name ?></title>

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
        .btn-danger{
            color:#fff !important;
        }
        .arrow{
            width: 22px;
            position: relative;
            display: inline-block;
            margin: 20px;
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .arrow::before, .arrow::after {
            position: absolute;
            display: block;
            content: "";
            background-color: currentColor;
            border-radius: 0;
            top: 0;
            width: 10px;
            height: 100%;
        }
        .arrow::before {
            left: 0;
            transform-origin: left top;
            transform: rotate(-45deg);
        }
        .arrow::after {
            left: 0;
            transform-origin: left bottom;
                transform: rotate(45deg);
        }
        .arrow__line{
            width: 100%;
            height: 2px;
            display: block;
            background-color: currentColor;
            border-radius: 0;
        }
    </style>
    
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
 
    <?php include(SERVER_BOOKING_PATH.'/screen/navbar.php'); ?>
 
    <section class="p-0 height-700 parallax-bg" style="overflow:hidden">
        <div id="demo" class="carousel slide" data-ride="carousel">

            
            
            <div class="carousel-inner">
                <?php
                
                    $sql = mysqli_query($conDB, "select * from herosection limit 5");
                    if(mysqli_num_rows($sql)>0){
                        $count = 0;
                        while($row = mysqli_fetch_assoc($sql)){
                            $img = FRONT_SITE_HERO_IMG.$row['img'];
                            $count ++;
                            if($count == 1){
                                $active = 'active';
                            }else{
                                $active = '';
                            }
                            echo "
                                <div class='carousel-item $active'>
                                    <img src='$img' alt='Los Angeles' width='1100' height='500'>
                                </div>
                            ";
                        }
                    }else{ ?>
                        <div class="carousel-item active">
                            <img src="images/hotel_slide/res1.jpg" alt="Los Angeles" width="1100" height="500">
                        </div>
                        <div class="carousel-item">
                            <img src="images/hotel_slide/res2.jpg" alt="Chicago" width="1100" height="500">
                        </div>
                        <div class="carousel-item">
                            <img src="images/hotel_slide/res3.jpg" alt="New York" width="1100" height="500">
                        </div>
                        <div class="carousel-item">
                            <img src="images/hotel_slide/res4.jpg" alt="Los Angeles" width="1100" height="500">

                        </div>
                  <?php  }
                
                ?>
                
            </div>

            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
        
    </section>


    <section id="date_select" style="position: relative;z-index: 7; background: white;">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <a href="<?php echo FRONT_BOOKING_SITE ?>" style="border-radius: 3px;display: inline-flex;justify-content: center;align-items: center;border: 1px dashed #0000003b;"><span class="arrow"><span class="arrow__line"></span></span></a>
                </div>
                <div class="col-4">
                    <p class="mb-3">Date: <input class="form-control" type='text' id='dateLoadPick' placeholder="<?php echo date('d F, y') ?>"></p>
                </div>
            </div>
            <!-- <div class="row dateloadrow">
                
                
            </div> -->
            
        </div>
    </section>
    

    <section id="packageDetail">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <div class="content">
                        <img src="<?php echo $pkimg ?>" alt="">
                        <h2><?php echo $name ?></h2>

                        <ul class="tabs">
                            <li class="tab-link current" data-tab="ITINERARY">ITINERARY</li>
                            <li class="tab-link" data-tab="POLICIES">POLICIES</li>
                            <!-- <li class="tab-link" data-tab="SUMMARY">SUMMARY</li> -->
                        </ul>

                        <div id="ITINERARY" class="tab-content current">
                            
                            <div class="day_plan">
                                <ul class="left_bar">
                                    <?php 
                                    $sl = 0;
                                        foreach(getPackageDayActivityArr($id) as $key=>$planList){
                                            $sl ++;
                                            $planId = $planList['id'];
                                            $timePerDay = date('d-M, Y',strtotime(getDateByDay($_SESSION['package']['checkIn'],$sl)));
                                            if($key == 0){
                                                echo "<li><a data-scroll='plan$planId' href='#plan$planId' class='act'>$timePerDay</a></li>";
                                            }else{
                                                echo "<li><a data-scroll='plan$planId' href='#plan$planId' >$timePerDay</a></li>";
                                            }
                                        }
                                    ?>
                                </ul>

                                <div class="right">
                                    <?php

                                    if($pickup == 'Yes'){
                                        $pickupPrint = "<span style='padding: 5px 15px;display: block;'> <input checked disabled type='checkbox' name='pickUp' id='pickUp'> <label for='pickUp'>Pick Up</label> </span>";
                                    }else{
                                        $pickupPrint = "<span style='padding: 5px 15px;display: block;'> <input type='checkbox' name='pickUp' id='pickUp'> <label for='pickUp'>Pick Up</label> </span>";
                                    }

                                    $AdultCount= getRoomAdultCountById($_SESSION['package']['rid']);
                                    $maxAdult = roomMaxCapacityById($_SESSION['package']['rid']);
                                    $adultPrint = '';
                                    for($i=1; $i<=$maxAdult; $i++){
                                        if($i == $noAdult){
                                            $adultPrint.= "<option selected value='$i'>$i</option>";
                                        }else{
                                            $adultPrint.= "<option value='$i'>$i</option>";
                                        }
                                    }

                                    $maxChild = roomMaxChildCapacityById($_SESSION['package']['rid']);
                                    $child = [0,1];
                                    $childPrint = '';
                                    foreach($child as $list){
                                        if($list == $noChild){
                                            $childPrint .= "<option selected value='$list'>$list</option>";
                                        }else{
                                            $childPrint .= "<option value='$list'>$list</option>";
                                        }
                                        
                                    }
                                    
                                        foreach(getPackageDayActivityArr($id) as $key=>$planList){
                                            $planId = $planList['id'];
                                            $planDesc = $planList['description'];
                                            if($key == 0){
                                                echo "<div class='holder' id='plan$planId'>
                                                        <div class='row contentRow' style='align-items: baseline;'>
                                                            <div class='col-4'>
                                                                <img src='$roomImg'>
                                                            </div>
                                                            <div class='col-8'>
                                                                <h2 style='text-align: left;padding: 10px 0;'>$roomName</h2>
                                                                <span style='display: flex;justify-content: start;align-items: center;'><small style='margin-right:10px'>Room Type</small> <h4>$roomType</h4></span>
                                                                <span style='display: flex;justify-content: start;align-items: center;'><small style='margin-right:10px'>Rate Plan</small> <h4>$ratePlan</h4></span>
                            
                                                            </div>
                                                            <div id='roomChangeBtn' style='display:none;position: absolute;top: 0;right: 0;padding: 10px;color: royalblue;font-weight: 700; cursor: pointer;'>Change</div>
                                                        </div>

                                                        $pickupPrint

                                                        <div class='row' style='padding: 0 15px;'>
                                                            <div class='col-md-6 form-group'>
                                                                <label for='adult'>Adult</label>
                                                                <select name='adult' id='adult' class='form-control'>
                                                                
                                                                $adultPrint
                                                                </select>
                                                            </div>
                                                            <div class='col-md-6 form-group'>
                                                                <label for='child'>Child</label>
                                                                <select name='child' id='child' class='form-control'>
                                                                    $childPrint                                                                    
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class='row contentRow' style='align-items: baseline;'>
                                                            <div class='col-4'>
                                                                <img src='$carImg'>
                                                            </div>
                                                            <div class='col-8'>
                                                                <h2 style='text-align: left;padding: 10px 0;'>$carName</h2>
                                                                <span style='display: flex;justify-content: start;align-items: center;'><small style='margin-right:10px'>Price</small> <h4>Free</h4></span>
                            
                                                            </div>
                                                            <div id='carChangeBtn' style='display:none;position: absolute;top: 0;right: 0;padding: 10px;color: royalblue;font-weight: 700; cursor: pointer;'>Change</div>
                                                        </div>
                                                        <div class='content' style='margin-top: 15px;'>$planDesc</div>
                                                    </div>";
                                            }else{
                                                echo "<div class='holder' id='plan$planId'>
                                                        <div class='content' style='margin-top: 15px;'>$planDesc</div>
                                                    </div>";
                                            }
                                        }
                                    
                                    ?>
                                </div>
                                
                            </div>
                        
                        </div>
                        <div id="POLICIES" class="tab-content">
                            <?php echo getPackagePolicy()['description'] ?>
                        </div>
                        <div id="SUMMARY" class="tab-content">
                            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </div>





                        <p><?php echo $description ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                <div style="position: relative;height: 100%;">
                    <div class="bookingSection">
                        
                            
                            <div id="priceBox">

                            
                                
                            </div>
                            

                            <button id="packageSubmitBtn">Proceed To Booking</button>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="formSubmitSection">
    <div class="content">
        <div class="closeBtn"><i class="fas fa-arrow-right"></i></div>
        <div class="boxContent">
            <form method="post" id="packageForm">
                <div class="form-group">
                    <label for="personName">Name</label>
                    <input required type="text" class="form-control" name="personName" id="personName">
                    <div id="personNameError"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="email">Email</label>
                        <input required type="text" class="form-control" name="email" id="email">
                        <div id="personNameError"></div>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="phone">Phone</label>
                        <input required type="text" class="form-control" name="phone" id="phone">
                        <div id="personNameError"></div>
                    </div>
                </div>
                
                <input type="hidden" name="type" value="packageSubmit">

                <div id="priceTable">
                    
                </div>

                <button type="submit" style="margin-top: 25px;" >Pay Now</button>
            </form>
        </div>
    </div>
</div>

    
    <?php include(SERVER_BOOKING_PATH.'/screen/footer.php') ?>

    
    
    <?php include(SERVER_BOOKING_PATH.'/screen/script.php') ?>
    </body>

         
            
        

    <script>
    
    
    $('.loadingbar').delay(500).animate({left: '0'}, 1500);
    $('.loadingBox').delay(500).animate({opacity: '1'}, 1000);
    $('#loadingScreen').delay(1500).animate({top: '-100%'}, 500);
    $('.loadingCircle').delay(4500).animate({opacity: '0'}, 500);

   
   
    $( function() {
        $( "#dateLoadPick" ).datepicker({dateFormat: "d MM, y", minDate:'0d'});
    } );


        function getPriceTag(){
            $.ajax({
                url: 'admin/include/ajax/package.php',
                type: 'post',
                data: {type: 'priceTag'},
                success: function(data){
                    $('#priceBox').html(data);
                }
            });
        }

        function personInput(){
            $.ajax({
                url: 'admin/include/ajax/package.php',
                type: 'post',
                data: {type: 'personInput'},
                success: function(data){
                    $('#personInput').html(data);
                }
            });
        }

        $('.roomBox.owl-carousel').owlCarousel({
            loop:false,
            margin:10,
            nav:false,
            responsive:{
                0:{
                    items:2
                },
                600:{
                    items:3
                },
                1000:{
                    items:4
                }
            }
        });

        $('.carBox.owl-carousel').owlCarousel({
            loop:false,
            margin:10,
            nav:false,
            responsive:{
                0:{
                    items:2
                },
                600:{
                    items:3
                },
                1000:{
                    items:5
                }
            }
        });


        $(document).ready(function(){
            getPriceTag();
            personInput();

            $('#packageForm').on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    url: 'admin/include/ajax/package.php',
                    type: 'post',
                    data: $('#packageForm').serialize(),
                    success: function(data){
                        if(data == 1){

                            $.ajax({
                                url: 'admin/include/ajax/package.php',
                                type: 'post',
                                data: {type: 'loadPaymentData'},
                                success: function(data){
                                    $arr = $.parseJSON(data);
                                    $price = $arr.price;
                                    $desc = $arr.desc;
                                    payment($price,$desc);
                                }
                            });
                        }
                    }
                })
            });

            $('ul.tabs li').click(function(){
                var tab_id = $(this).attr('data-tab');

                $('ul.tabs li').removeClass('current');
                $('.tab-content').removeClass('current');

                $(this).addClass('current');
                $("#"+tab_id).addClass('current');
            });


            $('.left_bar li').on('click', 'a[href^="#"]', function(e) {
                
                var id = $(this).attr('href');
                
                var $id = $(id);
                if ($id.length === 0) {
                    return;
                }
                
                e.preventDefault();
                
                var pos = $id.offset().top;
                
                $('body, html').animate({scrollTop: pos}, 900);
                });

                $(function() {
  
                    var link = $('.left_bar a');
                    
                    link.on('click', function(e) {
                        var target = $($(this).attr('href'));
                        $('html, body').animate({
                        scrollTop: target.offset().top
                        }, 600);
                        $(this).addClass('act');
                        e.preventDefault();
                    });
                    
                    $(window).on('scroll', function(){
                        scrNav();
                    });
                    
                    function scrNav() {
                        var sTop = $(window).scrollTop();
                        $('.holder').each(function() {
                        var id = $(this).attr('id'),
                            offset = $(this).offset().top-1,
                            height = $(this).height();
                        if(sTop >= offset && sTop < offset + height) {
                            link.removeClass('act');
                            $('.left_bar').find('[data-scroll="' + id + '"]').addClass('act');
                        }
                        });
                    }
                    scrNav();
                });

            $('.roomBox input').click(function(){
                if ($(this).is(":checked")) {
                var roomId = $(this).val();
                    $.ajax({
                        url: 'admin/include/ajax/package.php',
                        type: 'post',
                        data: {type: 'updateRoom', id:roomId},
                        success: function(data){
                            getPriceTag();
                            personInput();
                        }
                    })
                }
            });

            $('.carBox input').click(function(){
                if ($(this).is(":checked")) {
                var carId = $(this).val();
                    $.ajax({
                        url: 'admin/include/ajax/package.php',
                        type: 'post',
                        data: {type: 'updateCar', id:carId},
                        success: function(data){
                            getPriceTag();
                        }
                    })
                }
            });

            $('#pickUp').click(function(){
                if ($(this).is(":checked")) {
                var pickup = $(this).val();
                
                    $.ajax({
                        url: 'admin/include/ajax/package.php',
                        type: 'post',
                        data: {type: 'updatePickUp', price:pickup},
                        success: function(data){
                            getPriceTag();
                        }
                    });
                }else{
                    $.ajax({
                        url: 'admin/include/ajax/package.php',
                        type: 'post',
                        data: {type: 'removePickUp'},
                        success: function(data){
                            getPriceTag();
                        }
                    })
                }
            });

            $('#packageSubmitBtn').on('click',function(){
                $('#formSubmitSection').addClass('show');
                $.ajax({
                    url: 'admin/include/ajax/package.php',
                    type: 'post',
                    data: {type: 'loadPaymentTable'},
                    success: function(data){
                        $('#priceTable').html(data);
                    } 
                });
            });

            $('#formSubmitSection .closeBtn').on('click',function(){
                $('#formSubmitSection').removeClass('show')
            });
            
            $('#adult').change(function(){
                var adult = $(this).val();
                $.ajax({
                    url: 'admin/include/ajax/package.php',
                    type: 'post',
                    data: {type: 'updateadult', adult:adult},
                    success: function(data){
                        getPriceTag();
                    }
                })
            }); 
            
            $('#child').change(function(){
                var child = $(this).val();
                $.ajax({
                    url: 'admin/include/ajax/package.php',
                    type: 'post',
                    data: {type: 'updatechild', child:child},
                    success: function(data){
                        getPriceTag();
                    }
                })
            });
            
            $('#dateLoadPick').change(function(){
                var dateLoadPick = $(this).val();
                alert(dateLoadPick);
                $.ajax({
                    url: 'admin/include/ajax/package.php',
                    type: 'post',
                    data: {type: 'updateDate', dateLoadPick:dateLoadPick},
                    success: function(data){
                        getPriceTag();
                    }
                })
            });

            
            
        });



</script>

</html>