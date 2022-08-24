<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$page = $_POST['page'];

$bookId = BOOK_GENERATE.unique_id(5);
        
$checkIn = safeData($_POST['checkIn']);
$checkOut = safeData($_POST['checkOut']);
// $roomQuntity = safeData($_POST['roomQuntity']);
$reservationType = safeData($_POST['reservationType']);
$bookinSource = safeData($_POST['bookinSource']);
$businessSource = safeData($_POST['businessSource']);
$couponCode = safeData($_POST['couponCode']);
// $bookAvailable = safeData($_POST['bookAvailable']);

$selectRoom = $_POST['selectRoom'];
$selectRateType = $_POST['selectRateType'];
$selectAdult = $_POST['selectAdult'];
$selectChild = $_POST['selectChild'];

$guestName = safeData($_POST['guestName']);
$guestMobile = safeData($_POST['guestMobile']);
$guestEmail = safeData($_POST['guestEmail']);
$guestAddress = safeData($_POST['guestAddress']);
$guestCuntry = safeData($_POST['guestCuntry']);
$guestState = safeData($_POST['guestState']);
$guestCity = safeData($_POST['guestCity']);
$guestZip = safeData($_POST['guestZip']);

$paymentMethod = safeData($_POST['paymentMethod']);
$paidAmount = safeData($_POST['paidAmount']);

$reciptNo = generateRecipt();

$hotrlId = $_SESSION['ADMIN_ID'];



mysqli_query($conDB, "insert into booking(bookinId,hotelId,reciptNo,checkIn,checkOut,payment_status,bookingSource,bussinessSource,paymethodId,userPay,couponCode) values('$bookId','$hotrlId','$reciptNo','$checkIn','$checkOut','$reservationType','$bookinSource','$businessSource','$paymentMethod','$paidAmount','$couponCode')");

$lastId = mysqli_insert_id($conDB);



if(isset($selectRoom)){
    foreach($selectRoom as $key=> $val){
        $room = $val;
        $rateType = $selectRateType[$key];
        $adult = $selectAdult[$key];
        $child = $selectChild[$key];

        $roomPrice = getRoomPriceById($room,$rateType,$adult,$checkIn);
        $adultPrice = getAdultPriceByNoAdult($adult,$lastId,$room,$checkIn);
        $childPrice = getChildPriceByNoChild($child,$lastId,$room,$checkIn);
        if(isset(getRoomNumber('','',1,$room,$checkIn)[0])){
            
            $roomNum = getRoomNumber('',1,$room,$checkIn,$checkOut)[0]['roomNo'];
            mysqli_query($conDB, "insert into bookingdetail(bid,roomId,roomDId,adult,child,room_number) values('$lastId','$room','$rateType','$adult','$child','$roomNum')");
        }
    }
}

mysqli_query($conDB, "insert into guest(hotelId,bookId,roomnum,owner,name,email,phone,country) values('$hotrlId','$lastId','$roomNum','1','$guestName','$guestEmail','$guestMobile','$guestCuntry')");
$guestLastId = mysqli_insert_id($conDB);

echo $page;
?>