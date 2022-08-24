<?php
include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include(SERVER_BOOKING_PATH.'/admin/mpdf/autoload.php'); 

 if(!isset($_GET['oid']) && !isset($_GET['vid']) && !isset($_GET['qpid']) && !isset($_GET['qpvid']) ){
    redirect('index.php');
    die();
 }
 
 
 if(isset($_GET['vid'])){
    $vid=$_GET['vid'];
    $orderEmail=getBookingVoucher($vid);
    $fileName = getBookingIdById($vid).'_Hotel';
 }
 
 
  if(isset($_GET['oid'])){
    $oid=$_GET['oid'];
    $orderEmail=orderEmail($oid);
    $fileName = getBookingIdById($oid).'_Guest';
 } 
 
  if(isset($_GET['qpid'])){
    $qpid=$_GET['qpid'];
    $orderEmail=quickPayEmail($qpid);
    $fileName = getQuickPayBookingIdById($qpid).'_Guest';
 }
 
 if(isset($_GET['qpvid'])){
    $qpid=$_GET['qpvid'];
    $orderEmail=getQPVoucher($qpid);
    $fileName = getQuickPayBookingIdById($qpid).'_Hotel';
 }


$mpdf=new \Mpdf\Mpdf();
$mpdf->WriteHTML($orderEmail);
$file=$fileName.'.pdf';
$mpdf->Output($file,'D');


?>