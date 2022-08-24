(function ($) {

    let site = 'http://localhost/bePms/';

    function userRoomCheck($rid, $rdid, $room) {
        var id = $rdid;
        var room_id = $rid;
        var room = $room;
        var url = site + 'include/ajax/room.php';
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

    

    function roomCheckoutDateUpdate($key){
        var key = $key;
        $.ajax({
            url: site + "include/ajax/room_detail.php",
            type: 'post',
            data: { type: 'roomCheckoutDateUpdate',key: key},
            success: function (data) {
                var result = JSON.parse(data);
                console.log(result);
                $('#side_checkout .roomCheckoutDate.'+key).html(result.checkOut);
                $('#side_checkout .updateRoomGst.'+key).html(result.gst);
                $('#side_checkout .roomNightUpdate.'+key).html(result.night);
                $('#side_checkout .noOfight.'+key).val(result.noNight);
                $('#side_checkout .shortDateUpdate.'+key).html(result.shortDateUpdate);
                $('#side_checkout .totalRoomPriceupdate.'+key).html(result.total);
                $('#side_checkout .updateRoomTotalPrice.'+key).html(result.total);
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



    $(document).on('click', '#continue_btn', function () {
        $html = '<div class="book_detail">';
        $html += '<h4>Guest Details</h4>';
        $html += '<ul id="book_detail_action_btn"><li class="active">Personal</li><li>Business</li></ul>';
        $html += '<div class="content">';
        $html += '<form method="POST" id="personalDetailForm" action="' + site + 'pay">';
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

    $(document).on('click', '#backBookDetail', function (e) {
        e.preventDefault();
        $('#side_checkout .booking-summary-box').css({ 'display': 'block' });
        $('.add_room_detail').css('border-bottom', 'none');
        $('#continue_btn').show();
        $('#side_checkout #personalDetail').html('');
    })

    $(document).on('click', '#remove_guest_section', function () {
        $('.add_room_detail').css('border-bottom', 'none');
        $('.add_guest_btn').css("opacity", "1");
        $('#room_guest_select_form').remove();
    });

    $(document).on('click', '.close_side_checkout', function () {
        $('#side_checkout').hide();
        $('#side_checkout .content div').remove();
        $('#side_checkout .box').css({ 'max-width': '370px' });
        $('#side_checkout #personalDetail').html('');
    });

    $(document).on('click', '#closeBoxSection', function () {
        $('#side_checkout').hide();
        $('#side_checkout .content div').remove();
        $('#side_checkout .box').css({ 'max-width': '370px' });
        $('#side_checkout #personalDetail').html('');
    });


    $(document).on('submit', '#room_guest_select_form', function (e) {
        e.preventDefault();
        $.ajax({
            url: site + 'include/ajax/room.php',
            type: 'post',
            data: $('#room_guest_select_form').serialize(),
            success: function (data) {
                console.log(data);
                $('#side_checkout').show();
                loadCheckoutSection();
            }
        });
    });



    $(document).on('click', '#book_detail_action_btn li:last-child', function () {
        $('#book_detail_action_btn li').removeClass('active');
        $(this).addClass('active');
        $('#bussness_content').css('display', 'block');
    });

    $(document).on('click', '#book_detail_action_btn li:first-child', function () {
        $('#book_detail_action_btn li').removeClass('active');
        $(this).addClass('active');
        $('#bussness_content').css('display', 'none');
    });



    $(document).on('submit', '#personalDetailForm', function (e) {


        var name = $('#personName').val().trim();
        var email = $('#personEmail').val().trim();
        var phone = $('#personPhoneNo').val().trim();

        if (name == '') {
            e.preventDefault();
        } else if (email == '') {
            e.preventDefault();
        } else if (phone == '') {
            e.preventDefault();
        } else if (phone.length != 10) {
            e.preventDefault();
            alert('Invalid mobile No');
        } else {

            $.ajax({
                type: 'post',
                url: site + 'include/ajax/room.php',
                data: $('#personalDetailForm').serialize(),
                success: function (result) {
                }
            });
        }





    });


    function priceLoad() {
        $.ajax({
            url: site + 'include/ajax/room.php',
            type: 'post',
            data: { type: "LoadPrice" },
            success: function (data) {
                $('#totalPriceValue').html(data);
            }
        })
    }

    $(document).on('click', '#add_coupon', function () {
        var couponValu = $('#couponValue').val().trim();
        var error = $('#couponCodeError');
        error.html('');

        if (couponValu.length == '') {
            error.html('Coupon code required');
        } else {
            $.ajax({
                url: site + 'include/ajax/room.php',
                type: 'post',
                data: { type: 'couponCode', couponValu: couponValu },
                success: function (data) {
                    $arr = $.parseJSON(data);

                    if ($arr.type == 'success') {
                        loadCheckoutSection();
                    }
                    if ($arr.type == 'error') {
                        error.html($arr.msg);
                    }
                }
            });
        }

    });

    $(document).on('click', '#couponCloss', function () {
        $.ajax({
            url: site + 'include/ajax/room.php',
            type: 'post',
            data: { type: "removeCouponCode" },
            success: function (data) {
                loadCheckoutSection();
            }
        })

    });

    $(document).on('click', '#pickup', function () {
        if ($(this).is(':checked')) {
            $.ajax({
                url: site + 'include/ajax/room.php',
                type: 'post',
                data: { type: 'pickup' },
                success: function (data) {
                    priceLoad();
                    $html = '<span>Pick & Drop</span><span>Rs ' + data + '</span>';
                    $('#pickupContent').show().html($html);
                }
            });
        } else {
            $.ajax({
                url: site + 'include/ajax/room.php',
                type: 'post',
                data: { type: 'removePickup' },
                success: function (data) {
                    priceLoad();
                    $('#pickupContent').hide().html('');
                }
            });
        }
    });

    $(document).on('click', '#partial', function () {
        if ($(this).is(':checked')) {
            $.ajax({
                url: site + 'include/ajax/room.php',
                type: 'post',
                data: { type: 'partial' },
                success: function (data) {
                    priceLoad();
                }
            });
        } else {
            $.ajax({
                url: site + 'include/ajax/room.php',
                type: 'post',
                data: { type: 'removePartial' },
                success: function (data) {
                    priceLoad();
                }
            });
        }
    });

    $(document).on('click', '#payByRoom', function () {
        if ($(this).is(':checked')) {
            $.ajax({
                url: site + 'include/ajax/room.php',
                type: 'post',
                data: { type: 'payByRoom' },
                success: function (data) {
                    loadCheckoutSection();
                }
            });
        } else {
            $.ajax({
                url: site + 'include/ajax/room.php',
                type: 'post',
                data: { type: 'removePayByRoom' },
                success: function (data) {
                    loadCheckoutSection();
                }
            });
        }
    });

    $(document).on('click', '.payByRoom', function () {

        var key = [];
        $('.payByRoom[type=checkbox]:checked').each(function (i) {
            key[i] = $(this).val();
        });

        $.ajax({
            url: site + 'include/ajax/room.php',
            type: 'post',
            data: { type: 'payByRoomCalculate', key: key },
            success: function (data) {
                if (data > 0) {
                    priceLoad();
                    $('#partial').prop('disabled', true);
                } else {
                    loadCheckoutSection();
                }

            }
        });
    });

    var bigImg = $('#bigImg').attr('src');
    $('#img_overflow_content').attr('src', bigImg);

    $(document).on('click', '.smallImg', function () {
        var imgPath = $(this).attr('src');
        var imgId = $(this).data('id');
        $('#' + imgId + ' #bigImg').attr('src', imgPath);
        $('#img_overflow_content').attr('src', imgPath);
    });

    $(document).on('click', '.bigImgContent', function () {
        var imgPath = $(this).attr('src');
        $('#img_overflow_content').attr('src', imgPath);
        $('.img_overflow').show();
    });

    $(document).on('click', '.img_overflow .close', function () {
        $('.img_overflow').hide();
    });

    function nightManage($key, $night, $rid) {
        var night = $night;
        var key = $key;
        var rid = $rid;
        if (night <= 0) {
            night = 1;
        }
        $.ajax({
            url: site + 'include/ajax/room.php',
            type: 'post',
            data: { night: night, key: key, rid: rid },
            success: function (data) {
                roomCheckoutDateUpdate(key);
                priceLoad();
                if (data == 'noNight') {
                    alert('No Night');
                }
            }
        });
    }


    $(document).on('click', ('.quantity__minus'), function (e) {
        e.preventDefault();
        var target = $(this).siblings('input');
        var value = target.val();
        var key = $(this).data('key');
        var rid = $(this).data('rid');

        if (value > 1) {
            value--;
        }
        nightManage(key, value, rid)
    });

    $(document).on('click', ('.quantity__plus'), function (e) {
        e.preventDefault();
        var target = $(this).siblings('input');
        var value = target.val();
        var key = $(this).data('key');
        var rid = $(this).data('rid');
        value++;
        nightManage(key, value, rid);
    });

    $(document).on('click', '#closeSection', function () {
        $('#side_checkout').hide();
        $('#side_checkout .content div').remove();
        $('#side_checkout .box').css({ 'max-width': '370px' });
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
            url: site + 'include/ajax/room.php',
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

    $(document).on('click', ('.roomDecrement'), function (e) {
        e.preventDefault();
        var value = $('#roomInput').val();
        var id = $(this).data('id');
        var rdid = $(this).data('rdid');
        CheckRoomNum(id, rdid, value, 'dec');
    });

    $(document).on('click', ('.roomIncrement'), function (e) {
        var value = $('#roomInput').val();
        var id = $(this).data('id');
        var rdid = $(this).data('rdid');
        CheckRoomNum(id, rdid, value, 'inc');
    });

    $(document).on('click', '.closeGuestContent', function () {
        var key = $(this).data('key');

        $.ajax({
            url: site + 'include/ajax/room.php',
            type: 'post',
            data: { type: 'removeGustContent', key: key },
            success: function (data) {
                $('#roomInput').val(data);
                loadCheckoutSection();
            }
        });

    });

    $(document).on('click', '.guestContent', function () {
        $('.guestContent').removeClass('active');
        $(this).addClass('active');
    });


    $(document).on('click', '.CheckNight', function (e) {
        e.preventDefault();
        var date = $(this).data('date');
        var rid = $(this).data('rid');
        $.ajax({
            url: site + 'include/ajax/otherDetail.php',
            type: 'post',
            data: { type: 'nightChange', date: date, rid: rid },
            success: function (data) {
                loadRoomDetail();
                loadInputDate();
                loadCheckOutDate();
            }
        });
    });




})(jQuery);