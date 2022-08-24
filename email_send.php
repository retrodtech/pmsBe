<?php
include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

 if(!isset($_GET['oid']) && !isset($_GET['qpid'])){
    redirect('index.php');
    die();
 }

 if(isset($_GET['oid'])){

   $oid=$_GET['oid'];

   $sql = mysqli_query($conDB, "select * from booking where id = '$oid'");
   $booking_row = mysqli_fetch_assoc($sql);
   
   $guest = $booking_row['name'];
   
   $email = getOrderDetailByOrderId($oid)['email'];
   $body = orderEmail($oid);
   $sub = 'Your Booking Confirmed';
 }

 if(isset($_GET['qpid'])){

   $oid=$_GET['qpid'];

   $sql = mysqli_query($conDB, "select * from quickpay where id = '$oid'");
   $booking_row = mysqli_fetch_assoc($sql);
   
   $guest = $booking_row['name'];
   
   $email = $booking_row['email'];
   $body = quickPayEmail($oid);
   $sub = 'Your Quick Pay Success.';
 }
$hotel_email = hotelDetail()['email'];

send_email($email,$guest,$hotel_email,RETROD_BOOKING_EMAIL,$body,$sub);
$_SESSION['SuccessMsg'] = "Successfully Sent Email";
redirect('admin/booking.php');

?>