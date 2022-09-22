<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'config.php');


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



    <?php include(WS_BE_SERVER_SCREEN_PATH.'/head.php') ?>

    <title>
        <?php echo SITE_NAME ?> || Booking
    </title>

    <?php include(WS_BE_SERVER_SCREEN_PATH.'/script.php') ?>

    <style>
        a.btn-action {
            background: #222;
            color: #fff;
            padding: 9px 13px;
            margin: 0 0 0 15px;
        }

        .amenitie_list li::before {
            background: url('<?php echo WS_FRONT_SITE_IMG ?>icon/tick.svg') no-repeat center center;
        }

        .carousel-inner img {
            width: 100%;
        }

        .listroBoxmain::before {
            content: '';
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
            height: 4px;
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
            background: linear-gradient(to bottom, rgba(0, 0, 0, 1) 0%, rgba(125, 185, 232, 0) 100%);
            position: absolute;
            right: 50%;
            bottom: 50%;
            transform-origin: bottom right;
            z-index: 1;
            animation: rotateLoader 1.5s linear infinite;
        }

        @keyframes rotateLoader {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .btn-grad {
            color: #000 !important;
            cursor: pointer;
        }

        .btn-grad:hover {
            color: #fff !important;
        }

        .add_room_detail {
            display: flex;
            justify-content: flex-end;
        }

        .btn-danger {
            color: #fff !important;
        }


        .btn-outline-light {
            background: #005dab;
            border-color: #005dab;
            color: #ffffff;
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


<?php    

        $pageDirectory = 'page';

        if(!empty($_GET['page'])){
            $pageName = $_GET['page'];
            $pageFolder = scandir($pageDirectory, 0);
            unset($pageFolder[0],$pageFolder[1]);
            if(in_array($pageName.'.php', $pageFolder)){
            include($pageDirectory.'/'.$pageName.'.php');
            }else{
                include($pageDirectory.'/404.php');
            }
        }else{
            include($pageDirectory.'/index.php');
        }
                                                
    ?>

<div id="side_checkout">
    <div id="closeBoxSection"></div>
    <div class="box">
        <div class="close_side_checkout">X</div>
        <div id="content"></div>
        <div id="personalDetail"></div>
    </div>
</div>

<div class="img_overflow">
    <div class="close">X</div>
    <img id="img_overflow_content" src="" alt="">
</div>

<div id="verifyForm"></div>



<?php include(WS_BE_SERVER_SCREEN_PATH.'/footer.php') ?>





</body>



<script src="https://ebz-static.s3.ap-south-1.amazonaws.com/easecheckout/easebuzz-checkout.js"></script>


<script>


    $('.loadingbar').delay(500).animate({ left: '0' }, 1500);
    $('.loadingBox').delay(500).animate({ opacity: '1' }, 1000);
    $('#loadingScreen').delay(1500).animate({ top: '-100%' }, 500);
    $('.loadingCircle').delay(4500).animate({ opacity: '0' }, 500);


    $('#side_checkout').hide();

    $('#searcfForm').on('submit', function (e) {
        e.preventDefault();
        $('.formError').html('');
        var roomHeader = $('#roomHeader').val();
        var check_in_date = $('#check_in_date').val();
        var check_out_date = $('#check_out_date').val();
        var no_of_room = $('#no_of_room').val();
        var no_of_guest = $('#no_of_guest').val();

        var headerError = $('#headerError');
        var checkinError = $('#checkinError');
        var checkoutError = $('#checkoutError');
        var noroomError = $('#noroomError');
        var noGuestError = $('#noGuestError');

        if (roomHeader == '') {
            headerError.html('Room Requerd');
        } else if (check_in_date == '') {
            checkinError.html('Check In Requerd');
        } else if (check_out_date == '') {
            checkoutError.html('Check Out Requerd');
        } else if (no_of_room == '') {
            noroomError.html('Number of Room Requerd');
        } else if (no_of_guest == '') {
            noGuestError.html('Number of Guest Requerd');
        } else {
            $.ajax({
                url: 'include/ajax/search.php',
                type: 'post',
                data: $('#searcfForm').serialize(),
                success: function (data) {
                    $('#load_search').html(data);
                }
            });
        }
    })


    $(function () {
        var dateFormat = "dd/mm/yy",
            from = $("#check_in_date")
                .datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: dateFormat,
                    minDate: 0
                })
                .on("change", function () {
                    to.datepicker("option", "minDate", getDate(this));
                }),
            to = $("#check_out_date").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: dateFormat,
                minDate: 0
            })
                .on("change", function () {
                    from.datepicker("option", "maxDate", getDate(this));
                });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }
    }
    );

    var bigImg = $('#bigImg').attr('src');
    $('#img_overflow_content').attr('src', bigImg);

    $(function () {
        $("#dateLoadPick").datepicker();
    });

    function loadCheckoutSection() {
        $.ajax({
            url: 'include/ajax/room.php',
            type: 'post',
            data: { type: 'load_checkout_section', page: 'detail' },
            success: function (data) {
                $('#side_checkout #content').html(data);
                $('#side_checkout .box').css({ 'max-width': '370px' });
            }
        });
    }


    $(document).on('click', '#nav_togle', function () {
        $('header .side_content').toggleClass('active');
    });





    $(document).ready(function () {

        $(document).on('click', '#continue_btn', function () {
            $html = '<div class="book_detail">';
            $html += '<h4>Guest Details</h4>';
            $html += '<ul id="book_detail_action_btn"><li class="active">Personal</li><li>Business</li></ul>';
            $html += '<div class="content">';
            $html += '<form method="POST" id="personalDetailForm" action="">';
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

        $(document).on('submit', '#personalDetailForm', function (e) {
            e.preventDefault();


            var name = $('#personName').val().trim();
            var email = $('#personEmail').val().trim();
            var phone = $('#personPhoneNo').val().trim();

            if (name == '') {
                
            } else if (email == '') {
                
            } else if (phone == '') {
                
            } else if (phone.length != 10) {
                alert('Invalid mobile No');
            } else {

                $.ajax({
                    type: 'post',
                    url: "<?php echo WS_FRONT_SITE.'/include/ajax/room.php' ?>",
                    data: $('#personalDetailForm').serialize()+ '&slug=<?= $hotelSlug ?>',
                    success: function (result) {
                    }
                });


                $.ajax({
                    url: '<?= WS_FRONT_SITE.'/page/pay.php' ?>',
                    type: 'post',
                    data: $('#personalDetailForm').serialize() + '&slug=<?= $hotelSlug ?>',
                    success: function (data) {
                        var easebuzzCheckout = new EasebuzzCheckout('<?= $MERCHANT_KEY ?>', '<?= $ENV ?>');

                        var access_key = JSON.parse(data).data;

                        var options = {
                            access_key: access_key,
                            onResponse: (response) => {
                                var pid = response.easepayid;
                                var txnid = response.txnid;
                                var surl = response.surl;
                                var slug = response.udf1;

                                $.ajax({
                                    url: '<?= WS_FRONT_SITE.'/checkoutPay.php' ?>',
                                    type: 'post',
                                    data: { pid: pid, txnid: txnid, slug: slug },
                                    success: function (data) {
                                        if (data == 1) {
                                            window.location.href = surl;
                                        }
                                    }
                                });

                            },
                            theme: "#123456"
                        }
                        easebuzzCheckout.initiatePayment(options);
                    }
                });

            }



        });


        loadCheckoutSection();

        $('#footerDescReadMoreBtn').on('click', function () {
            $('#footerDescReadMoreBtn').hide();
            $('#footerDescReadLessCaption').slideDown();
        });
        $('#footerDescReadLessBtn').on('click', function () {
            $('#footerDescReadMoreBtn').show();
            $('#footerDescReadLessCaption').slideUp();
        });


        var swiper = new Swiper(".mySwiper", {
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
            },
        });




    });


    $('#packageSection .owl-carousel').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });



</script>

</html>