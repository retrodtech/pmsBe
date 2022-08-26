

<?php include(WS_BE_SERVER_SCREEN_PATH.'/navbar.php') ?>
    
<div class="innerpage-banner left bg-overlay-dark-7" style="padding: 20px 0;">
        <div class="container">
            <div class="row all-text-white justify-content-between">
                <div class="col-md-6 align-self-center">
                    <h1 class="innerpage-title">Thank You</h1>
                   <nav aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background-color: transparent;">
                            <li class="breadcrumb-item"><a href="<?php echo FRONT_BOOKING_SITE ?>"><i class="ti-home"></i> Home</a></li>
                            <li class="breadcrumb-item">Thank You</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6" style="display: flex;justify-content: end;">
                    <canvas width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    
    <?php
    
        if($_POST['status'] == 'success'){
            
            $bid = $_POST['txnid']; 
             
             $email = $_POST['email'];
             
             $payid = $_POST['easepayid'];
    
             mysqli_query($conDB,"update booking set payment_status='complete',payment_id='$payid' where bookinId='".$bid."'");
        

            $sql = mysqli_query($conDB, "select * from booking where bookinId = '$bid'");
            $booking_row = mysqli_fetch_assoc($sql);
            $bid = $booking_row['id'];
            $guestArry = getGuestDetail($bid, '1')[0];
           
            $guest = $guestArry['name'];
            $oid = $booking_row['id'];

            $hotel_email = hotelDetail()['email'];
            
            $pickupPrice = $booking_row['pickUp'];
            

            $orderDetail = getOrderDetailByOrderId($oid);

            // pr($orderDetail);
            
            $pickUp = $orderDetail['pickUp'];
            
            $couponCode = $booking_row['couponCode'];
            $pickupHtml = '';
            if($pickupPrice != 0){
                $pickupHtml = '
                    <tr>
                        <td class="bookex">Pick & Drop:</td>
                        <td>Rs '.$pickupPrice.'</td>
                    </tr>
                ';
            }
            
            
            $userPay = $orderDetail['userPay'];

            $grossCharge = 0;
            

            $getPayPercentage = getPercentageValueByAmount($userPay,$grossCharge);
            if($grossCharge > $userPay){
                $userPayHtml = '
                        <tr>
                            <td class="bookex"><strong>'.$getPayPercentage.'% Pay:</strong></td>
                            <td><strong>Rs '.$userPay.'</strong></td>
                        </tr>
                ';
            }
            $roomDetailHtml = '';
            $tootalRoomPrice = 0;
            $tootalAdultPrice = 0;
            $tootalChiltPrice = 0;
            $tootalGstPrice = 0;
            $totalBookingPrice = 0;
            foreach(getBookingDetailById($oid) as $bookinList){
                
                $rid = $bookinList['roomId'];
                $rdid = $bookinList['roomDId'];
                $adult = $bookinList['adult'];
                $child = $bookinList['child'];
                $noRoom = $bookinList['noRoom'];
                $night = $bookinList['night'];
                $roomPrice = $bookinList['roomPrice'];
                $childPrice = $bookinList['childPrice'];
                $adultPrice = $bookinList['adultPrice'];

                $checkIn = $bookinList['checkIn'];
                $checkout = $bookinList['checkout'];


                $couponCode = $couponCode;

                $singleRoomPriceCalculator = SingleRoomPriceCalculator($rid, $rdid, $adult, $child , $noRoom, $night, $roomPrice, $childPrice , $adultPrice, $couponCode);
                if($singleRoomPriceCalculator[0]['couponPrice'] == ''){
                    $couponPrice = 0;
                }else{
                    $couponPrice = $singleRoomPriceCalculator[0]['couponPrice'];
                }
                $roomPriceWithCoupon = $singleRoomPriceCalculator[0]['room'] - $couponPrice;

                $roomDetailHtml .= '
                        <tr>
                            <td class="bookex" style="text-align:left">
                                <div>'. getRoomNameById($rid) .' (<b>₹ '.$roomPriceWithCoupon.'</b>)</div> 
                                <div>Adult : <span>'. $adult .' (<b>₹ '.$adultPrice.'</b>)</span></div>
                            </td>
                            <td style="text-align:right">
                                <div>'.getDateFormatByTwoDate($checkIn,$checkout) .'</div> 
                                <div>Child : <span>'. $child .' (<b>₹ '.$childPrice.'</b>)</span></div>
                            </td>
                            
                        </tr>
                ';

                $tootalRoomPrice += $roomPriceWithCoupon;
                $tootalAdultPrice += $adultPrice;
                $tootalChiltPrice += $childPrice; 
                $tootalGstPrice += $singleRoomPriceCalculator[0]['gst']; 
                $totalBookingPrice += $singleRoomPriceCalculator[0]['total']; 
            }
    
            $totalBookingPrice += $pickupPrice;
            
            send_email($email,$guest,$hotel_email,RETROD_BOOKING_EMAIL,orderEmail($oid),'Your Booking Confirmed');
        }
        
        ?>
    
