<?php

$one_day = strtotime('1 day 00 second', 0);

$currentDate = date('Y-m-d',strtotime(date('Y-m-d')));

if(isset($_SESSION['checkIn'])){
    $currentDate = $_SESSION['checkIn'];
}

if(!isset($_SESSION['checkIn'])){
    $_SESSION['checkIn'] = $currentDate;
    $_SESSION['no_room'] = 1;
    $_SESSION['no_guest'] = 2;
    $_SESSION['night_stay'] = 1;
    $_SESSION['checkout'] = date('Y-m-d',$currentDate + (1 * $one_day));
}


if(!isset($_GET['id']) && empty($_GET['id'])){
    redirect('index.php');
    die();
}else{
    $slug = $_GET['id'];
    $sql = mysqli_query($conDB, "select * from room where slug = '$slug'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);

        $room_id = $row['id'];
        $header = $row['header'];
        $bedtype = $row['bedtype'];
        $roomcapacity = $row['roomcapacity'];
        $mrp = $row['mrp'];
        
        if($mrp != 0 ){
            $lowstPrice = $mrp - getRoomLowPriceByIdWithDate($room_id,  $_SESSION['checkIn']);
            $mrpPercentage = intval(($lowstPrice /  $mrp) * 100);
        }
    }else{
        $_SESSION['ErrorMsg'] = "Room Id Not Exist";
        redirect('index.php');
        die();
    }
}

$id = getRoomIdBySlug($_GET['id']);

?>


<?php include(WS_BE_SERVER_SCREEN_PATH.'/navbar.php'); echo "<input type='hidden' value='$id' id='PageId'>"; ?>

<section class="p-0 height-700 parallax-bg" style="overflow:hidden">
        <div id="demo" class="carousel slide" data-ride="carousel">

            
            
            <div class="carousel-inner">
                <?php
                
                    $sql = mysqli_query($conDB, "select * from herosection limit 5");
                    if(mysqli_num_rows($sql)>0){
                        $count = 0;
                        while($row = mysqli_fetch_assoc($sql)){
                            $img = WS_FRONT_SITE_IMG.'/hero/'.$row['img'];
                            $count ++;
                            if($count == 1){
                                $active = 'active';
                            }else{
                                $active = '';
                            }
                            echo "
                                <div class='carousel-item $active'>
                                    <img src='$img' alt='' width='1100' height='500'>
                                </div>
                            ";
                        }
                    } 
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
    <!-- =======================
	Main banner -->


    <section id="date_select" style="position: relative;z-index: 7; background: white;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-6">
                    <a href="<?php echo FRONT_BOOKING_SITE ?>" style="border-radius: 3px;display: inline-flex;justify-content: center;align-items: center;border: 1px dashed #0000003b;"><span class="arrow"><span class="arrow__line"></span></span></a>
                </div>
                <div class="col-md-6 col-6">
                <div class="row">
                    <div class="col-6">
                        <p class="mb-3" style="font-weight: 700;color: #1e90ff;">Checkin Date: <input class="form-control" type='text' id='dateLoadPick' value="" data-rdid="<?php echo getRoomIdBySlug($_GET['id']) ?>"></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-3" style="font-weight: 700;color: #1e90ff;">Checkout Date: <input class="form-control" type='text' id='dateLoadPickTo' value="" data-rdid="<?php echo getRoomIdBySlug($_GET['id']) ?>"></p>
                    </div>
                </div>

                </div>
            </div>
            
            <div id="loadRoomDate"></div>
        </div>
    </section>


    <section id="roomSection" class="Categories pt80 pb60 ">
        <div class="container">

        </div>
    </section>


    <script>
    

    function checkDateAvailableOrNot($date,$rdid){
    var date = $date;
    var rdid = $rdid;
    $.ajax({
        url: "<?php echo WS_FRONT_SITE.'/include/ajax/room_detail.php' ?>",
        type: 'post',
        data: { type: 'checkDateAvailableOrNot',date: date,rdid:rdid},
        success: function (data) {
            $('#checkDateAvailableOrNot').val(data);
        }
    });

    
}



function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");

  if (n > slides.length) {slideIndex = 1}   
   
  if (n < 1) {slideIndex = slides.length}

    //   for (i = 0; i < slides.length; i++) {
    //     slides[i].style.display = "none";  
    //   }
    //   console.log(slideIndex);
    //   slides[slideIndex-1].style.display = "block";  
} 


    $('.loadingbar').delay(500).animate({left: '0'}, 1500);
    $('.loadingBox').delay(500).animate({opacity: '1'}, 1000);
    $('#loadingScreen').delay(1500).animate({top: '-100%'}, 500);
    $('.loadingCircle').delay(4500).animate({opacity: '0'}, 500);
    
    
    $('#side_checkout').hide();

    var bigImg = $('#bigImg').attr('src');
    $('#img_overflow_content').attr('src', bigImg);

    $( function() {
        var array = $('#checkDateAvailableOrNot').val();
        console.log(array);
        $('#dateLoadPick').datepicker({
            minDate: 0,
            dateFormat: 'dd/mm/yy' ,
            beforeShowDay: function(date){
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [ array.indexOf(string) == -1 ]
            }
        });

        $('#dateLoadPickTo').datepicker({
            minDate: 0,
            dateFormat: 'dd/mm/yy' ,
            beforeShowDay: function(date){
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [ array.indexOf(string) == -1 ]
            }
        });

    } );

    function loadCheckoutSection() {
        $.ajax({
            url:"<?php echo WS_FRONT_SITE.'/include/ajax/room.php' ?>",
            type: 'post',
            data: { type: 'load_checkout_section',page: 'detail' },
            success: function (data) {
                if(data == ''){
                    $('#side_checkout').hide();
                    $('.add_room_detail').hide();
                    $('.add_guest_btn').css("opacity", "1");
                }else{
                    $('#side_checkout #content').html(data);
                    $('#side_checkout .box').css({ 'max-width': '370px'});
                }
            }
        });
    }

    function loadRoomDateSlide($date,$rdid){
        var date = $date;
        var rdid = $rdid;
        
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room_detail.php' ?>",
            type: 'post',
            data: { type: 'loadRoomDataSlide',date: date, rdid:rdid},
            success: function (data) {
                $('#loadRoomDate').html(data);
            }
        });
    }

    function loadRoomDetail(){
        var id = '<?php echo $_GET['id'] ?>';
        $('#roomSection .container').html('');
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room_detail.php' ?>",
            type: 'post',
            data: { type: 'loadRoom', id:id},
            success: function (data) {
                $('#roomSection .container').html(data);
            }
        });
    }

    function loadInputDate(){
        var rdid = $('#dateLoadPick').data('rdid');
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room_detail.php' ?>",
            type: 'post',
            data: { type: 'loadInputDate',},
            success: function (data) {
                $('#dateLoadPick').val(data);
                loadRoomDateSlide(data,rdid);
            }
        });
    }

    function loadCheckOutDate(){
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room_detail.php' ?>",
            type: 'post',
            data: { type: 'loadCheckOutDate',},
            success: function (data) {
                $('#dateLoadPickTo').val(data);
            }
        });
    }

    $('#dateLoadPick').change(function(){
        var date = $(this).val();
        var rdid = $('#dateLoadPick').data('rdid');
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room_detail.php' ?>",
            type: 'post',
            data: { type: 'addDate',date:date},
            success: function (data) {
                loadRoomDetail();
                loadCheckOutDate();
                loadRoomDateSlide(date,rdid);
            }
        });
    });

    $('#dateLoadPickTo').change(function(){
        var date = $(this).val();
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room_detail.php' ?>",
            type: 'post',
            data: { type: 'checkOutDate',date:date},
            success: function (data) {
                loadRoomDetail();
            }
        });
    });

    $(document).ready(function () {
        loadCheckoutSection();
        loadRoomDetail();
        loadInputDate();
        checkDateAvailableOrNot('2022-05-27','2');
        loadCheckOutDate();
        $('#footerDescReadMoreBtn').on('click',function(){
            $('#footerDescReadMoreBtn').hide();
            $('#footerDescReadLessCaption').slideDown();
        });
        $('#footerDescReadLessBtn').on('click',function(){
            $('#footerDescReadMoreBtn').show();
            $('#footerDescReadLessCaption').slideUp();
        });

        let slideIndex = 1;
        showSlides(slideIndex);
        var checkDate = $('#checkDateAvailableOrNot').val();
        console.log(checkDate);
    });

    $('.listroBox .owl-carousel').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        responsive:{
            0:{
                items:3
            },
            600:{
                items:4
            },
            1000:{
                items:5
            }
        }
    });

    function userRoomCheck($rid, $rdid, $room) {
        var id = $rdid;
        var room_id = $rid;
        var room = $room;
        var url =  "<?php echo WS_FRONT_SITE.'/include/ajax/room.php' ?>";
        $.ajax({
            url: url,
            type: 'post',
            data: { id: id, type: 'add_guest_section', room_id: room_id, room: room },
            success: function (data) {
                $('.add_room_detail').show();
                $('.add_room_detail' + id).html(data);
                $('.add_room_detail' + id).css({ 'border-bottom': '1px dashed #0000002b', "margin-bottom": "20px" });
            }
        });
    }

    $(document).on('click', '.add_guest_btn', function () {
      
        var id = $(this).data('id');
        var room_id = $(this).data('room');

        $('.add_guest_btn').css("opacity", "1");
        $(this).css("opacity", ".5");
        if ($("#room_guest_select_form").length > 0) {
            $('.add_room_detail').css({ 'border-bottom': 'none' });
            $('#room_guest_select_form').remove();
        }
        userRoomCheck(room_id, id, '1');

    });

    $(document).on('submit', '#room_guest_select_form', function (e) {
        e.preventDefault();
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room.php' ?>",
            type: 'post',
            data: $('#room_guest_select_form').serialize(),
            success: function (data) {
                console.log(data);
                $('#side_checkout').show();
                loadCheckoutSection();
            }
        });
    });

    $(document).on('click', '.CheckNight', function (e) {
        e.preventDefault();
        var date = $(this).data('date');
        var rid = $(this).data('rid');
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/otherDetail.php' ?>",
            type: 'post',
            data: { type: 'nightChange', date: date, rid: rid },
            success: function (data) {
                loadRoomDetail();
                loadInputDate();
                loadCheckOutDate();
            }
        });
    });

    $(document).on('click', '.closeGuestContent', function () {
        var key = $(this).data('key');

        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room.php' ?>",
            type: 'post',
            data: { type: 'removeGustContent', key: key },
            success: function (data) {
                $('#roomInput').val(data);
                loadCheckoutSection();
            }
        });

    });

    function CheckRoomNum($roomId, $roomDId, $room, $action) {
        var room = $room;
        var action = $action;
        var id = $roomId;
        var rdid = $roomDId;
        if (room <= 0) {
            room = 1;
        }
        $.ajax({
            url: "<?php echo WS_FRONT_SITE.'/include/ajax/room.php' ?>",
            type: 'post',
            data: { type: 'checkRoomNumber', room: room, rdid: rdid, action: action, id: id },
            success: function (data) {
                $('#roomInput').val(data);
                userRoomCheck(id, rdid, data);
                if (data == room) {
                    alert('Sold Out');
                }
            }
        });
    }

    $(document).on('click', ('.roomIncrement'), function (e) {
        var value = $('#roomInput').val();
        var id = $(this).data('id');
        var rdid = $(this).data('rdid');
        CheckRoomNum(id, rdid, value, 'inc');
    });

    $(document).on('click', ('.roomDecrement'), function (e) {
        e.preventDefault();
        var value = $('#roomInput').val();
        var id = $(this).data('id');
        var rdid = $(this).data('rdid');
        CheckRoomNum(id, rdid, value, 'dec');
    });


        $(document).on('click', '#continue_btn', function () {
            var site = '<?php echo WS_FRONT_SITE ?>';
            $html = '<div class="book_detail">';
            $html += '<h4>Guest Details</h4>';
            $html += '<ul id="book_detail_action_btn"><li class="active">Personal</li><li>Business</li></ul>';
            $html += '<div class="content">';
            $html += '<form method="POST" id="personalDetailForm" action="' + site + '/pay">';
            $html += '<div class="form-group"><label for="personName">Name</label><input type="text" class="form-content" name="personName" id="personName" required><div id="personNameError" ></div></div>';
            $html += '<div class="form-group"><label for="personEmail">Email</label><input type="email" class="form-content" name="personEmail" id="personEmail" required><div id="personEmailError" ></div></div>';
            $html += '<div id="bussness_content"><div class="form-group"><label for="companyName">Company name</label><input type="text" class="form-content" name="companyName" id="companyName"><div id="companyNameError"></div></div>';
            $html += '<div class="form-group"><label for="companyGst">Company GST</label><input type="text" class="form-content" name="companyGst" id="companyGst"></div><div id="companyGstError"></div></div>';
            $html += '<input type="hidden" value="persionCheckout" name="type">';
            $html += '<div class="form-group"><label for="personPhoneNo">Phone no</label><input type="number" class="form-content" name="personPhoneNo" id="personPhoneNo" required><div id="personPhoneNoError"></div></div>';
            $html += '<div class="form-group p10 row"><div class="col-6"><button id="backBookDetail" class="btn btn-light">Back</button></div> <div class="col-6"> <button id="continueBtnSubmit" type="submit" name="checkOutSubmit" class="btn btn-success">Pay Now</button>  </div></div>';
            $html += '</form>';
            $html += '</div></div>';
            $('#continue_btn').hide();
            $('#side_checkout .booking-summary-box').css({ 'display': 'none' });
            $('#side_checkout .booking-summary-box').addClass('m_none');

            $('#side_checkout #personalDetail').html($html);
        });

</script>