<?php

function redirect($link){
    ob_start();
    header('Location: '.$link);
    ob_end_flush();
    die();
}

function pr($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    die();
}

function safeData($data){
    global $conDB;
   return mysqli_real_escape_string($conDB, $data);
}

function str_openssl_dec($data,$iv){
    $key = KEY; 
    $cipher = "aes128"; 
    $option = 0; 
    return openssl_decrypt($data, $cipher, $key, $option, $iv);
}

function str_openssl_enc($data,$iv){
    $key = KEY; 
    $cipher = "aes128"; 
    $option = 0; 
    return openssl_encrypt($data, $cipher, $key, $option, $iv);
}

function ErrorMsg(){
    if(isset($_SESSION['ErrorMsg'])){
        $output = "<div class='alert error_box'><i class='ti-face-sad'></i>";
        $output .= $_SESSION['ErrorMsg'];
        $output .= "</div>";
        $_SESSION['ErrorMsg'] = null;
        return $output;
    }
}

function SuccessMsg(){
    if(isset($_SESSION['SuccessMsg'])){
        $output = "<div class='alert success_box'><i class='ti-face-smile'></i>";
        $output .= $_SESSION['SuccessMsg'];
        $output .= "</div>";
        $_SESSION['SuccessMsg'] = null;
        return $output;
    }
}

function checkLoginAuth(){
    if(!isset($_SESSION['ADMIN_ID']) && !isset($_SESSION['SUPER_ADMIN_ID'])){
        $_SESSION['ErrorMsg'] = "Please login";
        redirect('login.php');
      }
}

function convertArryToJSON($arry){
    return $arry;
}

function checkPageBySupperAdmin($pg='',$title='',$ttext=''){
    global $conDB;
    $hotelId = $_SESSION['ADMIN_ID'];
    $sql = "select * from hotel where status = '1' and id = '$hotelId'";
    if($pg == 'pms'){
        $sql .= " and pms = '1'";
    }
    $query = mysqli_query($conDB, $sql);
    if(mysqli_num_rows($query) > 0){

    }else{
        include(FO_SERVER_PATH.'/subscription.php');
        $html = subscriptionData($title,$ttext);
        echo  $html;
        die();
    }
    

}

function unique_id($l = 8){
    $better_token = md5(uniqid(rand(), true));
    $rem = strlen($better_token)-$l;
    $unique_code = substr($better_token, 0, -$rem);
    $uniqueid = $unique_code;
    return $uniqueid;

}

function checkImg($path,$demo=''){

    $data = $path; 
    

    if($demo == 'guest'){
        
        if($path == ''){
            $data = FRONT_SITE_IMG.'demo/person-icon.png';
        }else{
            $data = FRONT_SITE_IMG.'guest/'.$path;
        }
    }

    return $data;
}

function imgUploadWithData($img,$path,$oldImg=''){
    global $conDB;
    $image = $img['name'];
    $imageTemp = $img['tmp_name'];
    $extension=array('jpeg','jpg','JPG','png','gif');
    $ext=pathinfo($image,PATHINFO_EXTENSION);
    if(in_array($ext,$extension)){
        if($oldImg == ''){

        }else{
            unlink(SERVER_IMG.$path.'/'.$oldImg);
        }
        
        $newfilename=$path.'_'.rand(100000,999999).".".$ext;
        move_uploaded_file($imageTemp, SERVER_IMG.$path.'/'.$newfilename);  
        $data["img"] = $newfilename;
        $data['error'] = 'false';      
    }else{
        $data['error'] = 'true';
        $data['msg'] = 'Valid Image File Format';
    }

    return $data;
}

function generateRecipt(){
    global $conDB;
    $hotelId = $_SESSION['ADMIN_ID'];
    $sql = "select MAX(reciptNo) as recipt from booking where hotelId = '$hotelId'";
    $query = mysqli_query($conDB, $sql);

    $row = mysqli_fetch_assoc($query);

    $incRecipt = $row['recipt'] + 1;
    return generateNumberById($incRecipt);
}

function generateNumberById($oid){
    if($oid == ''){
        $oid = 0;
    }
    if(strlen($oid) == 1){
        $oid = "00".$oid;
    }elseif(strlen($oid) == 2){
        $oid = "0".$oid;
    }else{
        $oid = $oid;
    }

    return $oid;
}

function getRoomNumber($rNo='', $status = '', $rid='', $checkIn ='', $checkOut = '',$ridRes = ''){
    global $conDB;
    if($status != ''){
        $sql = "select * from roomnumber where status = '1'";
    }else{
        $sql = "select * from roomnumber where id != ''";
    }

    if($rNo != ''){
        $sql .= " and roomNo = '$rNo'";
    }

    if($rid != ''){
        $roomNumCheck = "";
        foreach(checkRoomNumberExiist($rid,$checkIn,$checkOut) as $roomNumList){
            $value = $roomNumList['room_number'];
            $roomNumCheck .= " and roomNo != '$value'";
        }
        if($ridRes != ''){
            $sql .= " and roomId = '$rid' $roomNumCheck";
        }else{
            $sql .= " and roomId = '$rid' ";
        }
        
    }

    $query = mysqli_query($conDB, $sql);
    $data = array();
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
    }

    return $data;
} 

function getGuestIdProofData($status='',$gip=''){
    global $conDB;
    if($status != ''){
        $sql = "select * from guestidproof where status = '1'";
    }else{
        $sql = "select * from guestidproof where id != ''";
    }

    if($gip != ''){
        $sql .= " and id = '$gip'";
    }

    $query = mysqli_query($conDB, $sql);
    $data = array();
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
    }

    return $data;
}

// Booking Detail Start

function getBookingIdByBVID($bvid){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select id from booking where bookinId = '$bvid'"));
    return $sql['id'];
}

function getBookingData($bid = '', $rNum = '', $checkIn='',$id='',$onlyCheckIn=''){
    global $conDB;
    $hotelId = $_SESSION['ADMIN_ID'];
    $query = "select booking.*,bookingdetail.*, bookingdetail.id as bookingdetailId from booking,bookingdetail where booking.id=bookingdetail.bid and booking.hotelId='$hotelId'";
    if($bid != ''){
        $query .= " and bookingdetail.bid = '$bid'";
    }
    if($rNum != ''){
        $query .= " and bookingdetail.room_number = '$rNum'";
    }
    if($id != ''){
        $query .= " and bookingdetail.id = '$id'";
    }
    if($checkIn != ''){
        if($onlyCheckIn != ''){
            $query .= " and booking.checkIn = '$checkIn' ";
        }else{
            $query .= " and booking.checkIn <= '$checkIn' and booking.checkOut > '$checkIn'";
        }
        
    }
    $sql = mysqli_query($conDB, $query);
    $data = array();
    if(mysqli_num_rows($sql) > 0){
        while($row = mysqli_fetch_assoc($sql)){
            $data[] = $row;
        }
    }
    return $data;
}

// function getBookDetailByRoomNumber ($rNo,$checkIn=''){
//     global $conDB;
//     $sql = mysqli_query($conDB, "select * from bookingdetail where room_number = $rNo");
//     $data = '';
//     if(mysqli_num_rows($sql) > 0){
//         $row = mysqli_fetch_assoc($sql);
//         $bid = $row['bid'];
//         $bookingData = getBookingData($bid,$checkIn);
//         $data = array_merge($row,$bookingData);
//     }
//     return $data;
// }

function getGuestDetail($bId='',$group='',$gid=''){
    global $conDB;
    $data =  array();
    $query = "select * from guest where id != ''";
    if($bId  != ''){
        $query .= " and bookId = '$bId'";
    }
    if($group != ''){
        $query .= " and owner = '1'";
    }
    if($gid  != ''){
        $query .= " and id = '$gid'";
    }
    $sql = mysqli_query($conDB, $query);
    if(mysqli_num_rows($sql) > 0){
        while($row = mysqli_fetch_assoc($sql)){
            $data[] = $row;
        }
    }
    return $data;
}

function getBookingDetailById($bid,$roomNo=''){
    global $conDB;

    $checkIn = getBookingData($bid)[0]['checkIn'];
    $checkOut = getBookingData($bid)[0]['checkOut'];
    $night = getNightByTwoDates($checkIn, $checkOut);

    $guestRow = array();
    $questQuery = "select * from guest where bookId = '$bid'";
    if($roomNo != ''){
        $questQuery .= " and roomnum = '$roomNo'";
    }
    $guestSql = mysqli_query($conDB, $questQuery);
    if(mysqli_num_rows($guestSql)>0){
        while($row = mysqli_fetch_assoc($guestSql)){
            $guestRow[] = $row;
        }
    }
    $name = '';
    $guest = array();
    $totalAdult = 0;
    $totalChild = 0;
    $bookingQuery = "select * from bookingdetail where bid = '$bid'";
    if($roomNo != ''){
        $bookingQuery .= " and room_number = '$roomNo'";
    }
    $bookingSql = mysqli_query($conDB, $bookingQuery);
    $subTotalPrice = 0;

    if(mysqli_num_rows($bookingSql) > 0){
        while($row = mysqli_fetch_assoc($bookingSql)){
            $adult = $row['adult'];
            $child = $row['child'];

            $roomId = $row['roomId'];
            $roomDId = $row['roomDId'];

            $roomPrice = getRoomPriceById($roomId,$roomDId,$adult,$checkIn);
            $adultPrice = getAdultPriceByNoAdult($adult,$roomId,$roomDId,$checkIn);
            $childPrice = getChildPriceByNoChild($child,$roomId,$roomDId,$checkIn);

            $subTotalPrice += $roomPrice + $adultPrice + $childPrice;
            $totalAdult += $adult;
            $totalChild += $child;
        }
    }

    foreach($guestRow as $key => $val){
        if($key == 0){
            $name = $val['id'];
        }
        $guest[] =  $val['id'];
    };

    $subTotalPrice = $subTotalPrice * $night;

    $totalPrice = $subTotalPrice + getPercentageValu($subTotalPrice, 12);

    $data = [
        'name'=>$name,
        'guest'=>$guest,
        'totalAdult'=> $totalAdult,
        'totalChild'=> $totalChild,
        'night'=> $night,
        'subTotalPrice'=>$subTotalPrice,
        'totalPrice'=>$totalPrice
    ];

    return $data;
}



// Booking Detail End

function getRoomNameType($rtid = ''){
    global $conDB;
    $sql = "select * from room";

    if($rtid != ''){
        $sql .= " where id='$rtid'";
    }

    $query = mysqli_query($conDB, $sql);

    $row = array();
    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_assoc($query);
    }

    return $row;
}

function getDateFormatByTwoDate($date,$date2){
    $dateString = date('M-d', strtotime($date));
    $date2String = date('M-d', strtotime($date2));

    $dateArr = explode('-',$dateString);
    $date2Arr = explode('-',$date2String);

    return $dateArr[0].' '.$dateArr[1].' - '. $date2Arr['1'];
}

function checkGuestCheckInStatus($status=''){
    global $conDB;
    
    $data = array();
    if($status != ''){
        $sql = mysqli_query($conDB, "select * from check_in_status where id = '$status'");
    }else{
        $sql = mysqli_query($conDB, "select * from check_in_status");
    }

    while($row = mysqli_fetch_assoc($sql)){
        $data[] = [
            'name'=>$row['name'],
            'clr'=>$row['color']
        ];
    };
    

    return $data;
}

function getPaymentTypeMethod($pid = '',$status  = ''){
    global $conDB;
    if($status != ''){
        $sql = "select * from banktypemethod where status = 1";
    }else{
        $sql = "select * from banktypemethod where id != ''";
    }
    if($pid != ''){
        $sql .= " and pid = '$pid'";
    }
    $query = mysqli_query($conDB, $sql);
    if(mysqli_num_rows($query) > 0){
        while ($row = mysqli_fetch_assoc($query) ) {
            $data[] = $row;
        }
    }

    return $data;
}

//    Frontoffice function 


function getBookingSource($bsid = ''){
    global $conDB;
    $sql = "select * from bookingsource where status = '1'";
    if($bsid != ''){
        $sql .= " and id = '$bsid'";
    }

    $data = array();
    $query = mysqli_query($conDB,$sql);
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
    }

    return $data;
}

function getReservationType($rid = ''){
    global $conDB;
    $sql = "select * from reservationtype";
    if($rid != ''){
        $sql .= " where id = '$rid'";
    }

    $data = array();
    $query = mysqli_query($conDB,$sql);
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
    }

    return $data;
}

function getCashiering($tpe = '',$bs = '',$cid = '',$status=''){
    global $conDB;
    $sql = "select * from cashiering";
    if($tpe != '' || $bs != '' || $cid != '' || $status != ''){
        $sql .= " where status = '1'";
    }
    if($tpe != ''){
        $sql .= " and type = '$type'";
    }
    if($bs != ''){
        $sql .= " and bookingSource like '%$bs%'";
    }
    if($cid != ''){
        $sql .= " and id = '$cid'";
    }

    $data = array();
    $query = mysqli_query($conDB,$sql);
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
    }

    return $data;
}

function getRoomType($rid = '', $status = ''){
    global $conDB;
    $sql = "select * from room";
    if($rid != '' || $status != ''){
        $sql .= " where status = '1'";
    }
    if($rid != ''){
        $sql .= " and id = '$rid'";
    }

    $data = array();
    $query = mysqli_query($conDB,$sql);
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
    }

    return $data;
}

function getRateType($rid = '',$rdid = '', $status = ''){
    global $conDB;
    $sql = "select * from roomratetype";
    if($rid != '' || $status != '' || $rdid != ''){
        $sql .= " where status = '1'";
    }
    if($rid != ''){
        $sql .= " and room_id = '$rid'";
    }
    if($rdid != ''){
        $sql .= " and id = '$rdid'";
    }

    $data = array();
    $query = mysqli_query($conDB,$sql);
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[] = $row;
        }
    }

    return $data;
}

function getMaxAdultCountByRId($rid){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from room where id = '$rid'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
        $data = $row['roomcapacity'];
    }
    return $data;
}

function getNoAdultCountByRId($rid){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from room where id = '$rid'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
        $data = $row['noAdult'];
    }
    return $data;
}

function getNightByTwoDates($date1,$date2){
    $earlier = new DateTime($date1);
    $later = new DateTime($date2);

    $abs_diff = $later->diff($earlier)->format("%a");
    return $abs_diff;
}

function getCountChildData($rid,$nAdult = ''){
    global $conDB;
    $maxAdult = getMaxAdultCountByRId($rid);
    $minAdult = getNoAdultCountByRId($rid);
    if($nAdult != ''){
        $minAdult = $nAdult;
    }

    $data = $maxAdult - $minAdult;
    return $data;
}

function getGSTPercentage($price){
    if($price <= 999){
        $data = 0;
    }elseif($price <= 7499){
        $data = 12;
    }else{
        $data = 18;
    }
    return $data;
}

function getGSTPrice($price){
    if($price <= 999){
        $gstprice = 0;
    }elseif($price <= 7499){
        $gstprice = $price * 12 / 100;
    }else{
        $gstprice = $price * 18 / 100;
    }
    return $gstprice;
}

function couponActualPrice($code,$price){
    global $conDB;
        $sql = mysqli_query($conDB, "select * from couponcode where coupon_code = '$code'");
        $row = mysqli_fetch_assoc($sql);
        $coupon_type = $row['coupon_type'];
        $coupon_value = $row['coupon_value'];
        $totalPrice = 0;
        
        if($coupon_type == 'P'){
            $totalPrice = $price * ($coupon_value / 100);
        }
        if($coupon_type == 'F'){
            $totalPrice = $coupon_value;
        }
        return  $totalPrice;
}

function getRoomPriceById($rid,$rdid, $nadult, $date ,$date2=''){
    global $conDB;
  
    $countAdult= getMinRoomAdultCountById($rid);
    if($countAdult < $nadult){
        $nadult = $countAdult;
    }
    if($nadult > 2){
        $nadult = 2;
    }
    if($nadult == 1){
        $sql = "select price as price from inventory where room_id = '$rid' and room_detail_id = '$rdid'  and add_date = '$date'  and price != 'Null' and price != '0'";
        $query = mysqli_query($conDB,$sql);
        if(mysqli_num_rows($query)>0){
            $inven_row = mysqli_fetch_assoc($query);
            $price = $inven_row['price'];
        }
    }
    if($nadult == 2){
        $sql = "select price2 as price from inventory where room_id = '$rid' and room_detail_id = '$rdid'  and add_date = '$date'  and price != 'Null' and price2 != '0'";
        $query = mysqli_query($conDB,$sql);
        if(mysqli_num_rows($query)>0){
            $inven_row = mysqli_fetch_assoc($query);
            $price = $inven_row['price'];
        }else{
            $sql = "select doublePrice as price from roomratetype where room_id = '$rid' and id='$rdid' and doublePrice != 0";
            $query = mysqli_query($conDB,$sql);
            if(mysqli_num_rows($query)>0){
                $inven_row = mysqli_fetch_assoc($query);
                $price = $inven_row['price'];
            }
        }
    }
    if(!isset($price)){
        if($nadult == 1){
            $sql = "select singlePrice as price from roomratetype where room_id = '$rid' and id='$rdid'";
            $query = mysqli_query($conDB,$sql);
            if(mysqli_num_rows($query)>0){
                $inven_row = mysqli_fetch_assoc($query);
                $price = $inven_row['price'];
            }
        }
        if($nadult == 2){
            $sql = "select doublePrice as price from roomratetype where room_id = '$rid' and id='$rdid' and doublePrice != 0";
            $query = mysqli_query($conDB,$sql);
            if(mysqli_num_rows($query)>0){
                $inven_row = mysqli_fetch_assoc($query);
                $price = $inven_row['price'];
            }else{
                $sql = "select singlePrice as price from roomratetype where room_id = '$rid' and id='$rdid'";
                $query = mysqli_query($conDB,$sql);
                if(mysqli_num_rows($query)>0){
                    $inven_row = mysqli_fetch_assoc($query);
                    $price = $inven_row['price'];
                }
            }
        }
    }

    return $price;
}

function getRoomAdultCountById($rid){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select max(room.roomcapacity) as maxAdult from room,roomratetype where room.id=roomratetype.room_id and roomratetype.room_id = '$rid'"));
    return $sql['maxAdult'];
}

function getMinRoomAdultCountById($rid){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select * from room,roomratetype where room.id=roomratetype.room_id and roomratetype.room_id = '$rid'"));
    $data = $sql['noAdult'];
    return $data;
}

function getMinRoomAdultCountByIdRdid($rid,$rdid=''){
    global $conDB;
    $query = mysqli_query($conDB, "select room.*,roomratetype.*, roomratetype.id as room_detailId from room,roomratetype where room.id=roomratetype.room_id and roomratetype.room_id = '$rid' and  roomratetype.id = '$rdid'");
    $sql = mysqli_fetch_assoc($query);
    $single = getRoomPriceById($rid,$rdid, 1, date('Y-m-d'));
    $double = getRoomPriceById($rid,$rdid, 2, date('Y-m-d'));
    if($single == $double){
        $data = $sql['noAdult'];
    }elseif($double == 0){
        $data = 1;
    }elseif($single < $double){
        $data = 1;
    }
    return $data;
}

function getRoomChildCountById($rid){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select max(room.noChild) as maxChild from room,roomratetype where room.id=roomratetype.room_id and roomratetype.room_id = '$rid'"));
    return $sql['maxChild'];
}

function getRoomExtraAdultPriceById($rdid,$date=''){
    global $conDB;
    $invenSql = mysqli_query($conDB, "select eAdult from inventory where room_detail_id = '$rdid' and add_date = '$date' and eAdult != '0'");
    if(mysqli_num_rows($invenSql) > 0){
        $row = mysqli_fetch_assoc($invenSql);
        $price = $row['eAdult'];
    }else{
        $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select extra_adult from roomratetype where id = '$rdid'"));
        $price = $sql['extra_adult'];
    }
    
    return $price;
}

function getAdultPriceByNoAdult($n,$rid,$rdid,$date=''){
    if(getRoomAdultCountById($rid) >= $n){
        $data = 0;
    }else{
        $data = ($n - getRoomAdultCountById($rid)) * getRoomExtraAdultPriceById($rdid,$date);
    }
    return $data;
}

function getRoomExtraChildPriceById($rdid,$date=''){
    global $conDB;
    $invenSql = mysqli_query($conDB, "select eChild from inventory where room_detail_id = '$rdid' and add_date = '$date' and eChild != '0'");
    if(mysqli_num_rows($invenSql) > 0){
        $row = mysqli_fetch_assoc($invenSql);
        $price = $row['eChild'];
    }else{
        $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select extra_child from roomratetype where id = '$rdid'"));
        $price = $sql['extra_child'];
    }
    
    return $price;
}

function getChildPriceByNoChild($n,$rid,$rdid,$date=''){
    if(getRoomChildCountById($rid) >= $n){
        $data = 0;
    }else{
        $data = ($n - getRoomChildCountById($rid) ) * getRoomExtraChildPriceById($rdid,$date);
    }
    return $data;
}

function getRoomLowPriceById($rid, $date){
    global $conDB;
    $data=array();
    if(isset($_SESSION['checkout'])){
        $date2 = $_SESSION['checkout'];
    }else{
        $oneDay = strtotime('1 day 30 second', 0);
        $date2 = date('Y-m-d',strtotime($date) + $oneDay);
    }
    $sql = "select * from inventory where room_id = '$rid' and add_date <= '$date'  and price !='' order by price desc";
    $inven_sql = mysqli_query($conDB, $sql);
    if(mysqli_num_rows($inven_sql)>0){
        while($inven_row = mysqli_fetch_assoc($inven_sql)){
            $price=$inven_row['price'];
        }
    }else{
        $sql = "select * from room_detail where room_id = '$rid' and price !='' order by price desc";
        $inven_sql = mysqli_query($conDB, $sql);
            while($inven_row = mysqli_fetch_assoc($inven_sql)){
                $price=$inven_row['price'];
            }
    }
    
    return $price;
}

function getRoomLowPriceByIdWithDate($rid, $date ,$date2=''){
    global $conDB;
    if($date2 == ''){
        $date2 = $date;
    }
    $data=array();
    $sql = "select * from inventory where room_id = '$rid' and add_date = '$date' and price !='' order by price desc";
    $inven_sql = mysqli_query($conDB, $sql);
    if(mysqli_num_rows($inven_sql)>0){
        while($inven_row = mysqli_fetch_assoc($inven_sql)){
            $price=$inven_row['price'];
        }
    }else{
        $sql = "select * from roomratetype where room_id = '$rid' order by singlePrice desc";
        $inven_sql = mysqli_query($conDB, $sql);
            while($inven_row = mysqli_fetch_assoc($inven_sql)){
                $price=$inven_row['singlePrice'];
            }
    }
    
    return $price;
}

function getSingleRoomPrice($rid, $rdid, $adult, $child ,$date, $nNight,$couponCode=''){
    global $conDB;

    $singleRoom = getRoomPriceById($rid,$rdid, $adult, $date);
    $adultPrice = getAdultPriceByNoAdult($adult,$rid,$rdid, $date);
    $childPrice = getChildPriceByNoChild($child,$rid,$rdid, $date);

    $roomPrice = $singleRoom; 
    $couponPrice = '';
    
    if($couponCode != ''){
        $couponPrice = couponActualPrice($couponCode,$roomPrice);
        $roomPrice = $roomPrice - $couponPrice;
    }
    
    $nightPrice = $roomPrice + $adultPrice + $childPrice;

    $totalRoomPrice = ($nightPrice  * $nNight) ;

    $gstper = getGSTPercentage($roomPrice);
    
    $gst = ($totalRoomPrice * $gstper) / 100;
    if($gstper == 0){
        $gst = 0;
    }

    $totalPrice = $totalRoomPrice + $gst;
    $nightPriceHtml = $nightPrice;
    if($nNight > 1){
        $nightPriceHtml = $nightPrice.' * '.$nNight;
    }


    $data = array();
    
    $data= [
        'room' => $singleRoom,
        'adultPrint' => $adult,
        'childPrint' => $child,
        'adult' => $adultPrice,
        'child' => $childPrice,
        'noNight' => $nNight,
        'night' => $nightPrice,
        'nightPrice' => $nightPriceHtml,
        'couponCode' => $couponCode,
        'couponPrice' => $couponPrice,
        'gstPer' => $gstper,
        'gst' => $gst,
        'total' => $totalPrice
        ];
    
    return $data;
}

function getPercentageByTwoValue($first,$sec){
    $data = $first * 100 / $sec;
    return round($data);
}

function getPercentageValu($amount, $value){
    $data = $value * $amount / 100;

    return $data;
}

function checkRoomNumberExiist($rId, $checkIn='',$checkOut='',$rnum = ''){
    global $conDB;
    // $sql = "select booking.checkIn, bookingdetail.room_number from booking, bookingdetail where booking.id = bookingdetail.bid and booking.checkIn= '$checkIn' and bookingdetail.roomId = '$rId'";
    $sql ="SELECT  booking.checkIn, booking.checkOut, bookingdetail.room_number FROM booking, bookingdetail
        WHERE
            booking.id = bookingdetail.bid AND bookingdetail.roomId = '$rId' AND (
                booking.checkIn <= '$checkIn' AND booking.checkOut >= '$checkIn' AND booking.checkOut <= '$checkOut'
            ) OR(
                booking.checkIn >= '$checkIn' AND booking.checkOut <= '$checkOut'
            ) OR(
                booking.checkIn >= '$checkIn' AND booking.checkOut >= '$checkIn' AND booking.checkOut >= '$checkOut' AND booking.checkIn <= '$checkOut'
            ) OR(
                booking.checkIn <= '$checkIn' AND booking.checkOut >= '$checkOut'
            )";

            if($rnum != ''){
                $sql .= " and bookingdetail.room_number = '$rnum'";
            }
    $query = mysqli_query($conDB, $sql);
    $data = array();
    while($row = mysqli_fetch_assoc($query)){
        $data[] = $row;
    }


    return $data;
}

function countBookingRow($reservation='',$arrive='',$failed='',$inHouse=''){
    global $conDB;
    $currentDate = date('y-m-d'); 
    $sql = "select booking.*,bookingdetail.checkinstatus from booking,bookingdetail where booking.id != ''";
    

    if($reservation != ''){        
        $sql = "select booking.*,bookingdetail.checkinstatus from booking,bookingdetail where bookingdetail.checkinstatus = '1' and booking.payment_status = '1'";
    }

    if($arrive != ''){        
        $sql = "select booking.*,bookingdetail.checkinstatus from booking,bookingdetail where booking.checkIn = '$currentDate' and booking.payment_status = '1'";
    }

    if($failed != ''){        
        $sql = "select booking.*,bookingdetail.checkinstatus from booking,bookingdetail where booking.payment_status = 'pending'";
    }

    if($inHouse != ''){        
        $sql = "select booking.*,bookingdetail.checkinstatus from booking,bookingdetail where bookingdetail.checkinstatus = '2' and booking.payment_status = '1'";
    }
    
    $sql .= " and booking.id=bookingdetail.bid ";

    $query = mysqli_query($conDB, $sql);

    $data = mysqli_num_rows($query);

    return $data;

}

function getPageName($page){
    $page = explode('/',$page);
    return explode('.',end($page))[0];
}



// Reservation

function reservationContent($bid,$reciptNo,$gname,$checkIn,$checkOut,$bDate,$nAdult,$nChild,$total,$paid,$preview=''){
    if($checkIn == ''){
        $checkIn = date('Y-m-d');
    }
    if($checkOut == ''){
        $checkOut = date("Y-m-d", strtotime("1 day", strtotime(date('Y-m-d'))));
    }
    $actionCon = '';
    if($gname == ''){
        $gname = '_ _ _';
    }
    if($total == ''){
        $total = 0;
    }
    if($paid == ''){
        $paid = 0;
    }
    if($nAdult == ''){
        $nAdult = 0;
    }
    if($nChild == ''){
        $nChild = 0;
    }
    $gname = ucfirst($gname);
    $checkInOut = getDateFormatByTwoDate($checkIn,$checkOut);
    $totalAmount = number_format($total,2);
    $paidAmount = number_format($paid,2);
    $pending = number_format($total - $paid,2);
    $countNight = getNightByTwoDates($checkIn, $checkOut);
    $previewContent = '';

    if($preview == 'yes'){
        $previewContent = "
        
                <div class='foot'>
                    <ul>
                        <li>
                            <a href='javascript:void(0)'><i class='fas fa-print'></i></a>
                        </li>

                        <li>
                            <a href='javascript:void(0)'><i class='far fa-envelope-open'></i></a>
                        </li>

                        <li>
                            <a href='javascript:void(0)'><i class='far fa-file-alt'></i></a>
                        </li>
                    </ul>
                </div>
        
        ";
    }



    $html = "
            <div class='reservationContent' data-bookingId='$bid'>
                            
                <div class='head'>
                    <div class='leftSide'>
                        <div class='icon'><i class='fas fa-user'></i></div>
                        <div class='userName'>
                            <h4>$gname</h4>
                            <p> $reciptNo / $bid </p>
                        </div>
                    </div>
                    <div class='rightSide'>
                        $actionCon
                        
                    </div>
                </div>

                <div class='body'>
                    <div class='checkInDetail'>
                        <div class='left'>
                            <strong>$checkInOut</strong>
                        </div>
                        <div class='right'>
                            <span>Night </span>
                            <strong>$countNight</strong>
                        </div>
                    </div>
                    <div class='bookingDate'>
                        <div class='left'>
                            <strong>Booking Date:- </strong>
                            <span>$bDate</span>
                        </div>
                        <div class='right'>
                            <ul>
                                <li>
                                    <i class='fas fa-male'></i>
                                    <strong>$nAdult</strong>
                                </li>
                                <li>
                                    <i class='fas fa-child'></i>
                                    <strong>$nChild</strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class='bookingDetail'>
                        <ul>
                            <li>
                                <small>Total</small>
                                <strong>Rs $totalAmount</strong>
                            </li>
                            <li>
                                <small>Paid</small>
                                <strong>Rs $paidAmount</strong>
                            </li>
                            <li>
                                <small>Due Amount</small>
                                <strong>Rs $pending</strong>
                            </li>
                        </ul>
                    </div>

                </div>

                $previewContent

            </div>
        ";

        return $html;
}





// Web Builder Function 


function getSlider($sid=''){
    global $conDB;
    $sidStatus = '';
    if($sid != ''){
        $sidStatus = " where id = '$sid'";
    }
    $sql = mysqli_query($conDB, "select * from herosection $sidStatus");
    $data = array();
    
    if(mysqli_num_rows($sql)>0){
        while($row = mysqli_fetch_assoc($sql)){
            $data[] = [
                    
                    'id'=>$row['id'],
                    'title'=>$row['title'],
                    'subtitle'=>$row['subTitle'],
                    'img'=>$row['img'],
                    'status'=>$row['status']
                ];
        }
    }else{
        $data = array();
    }
    return $data;
}


function inventoryCheck($date, $rid='', $rdid=''){
    global $conDB;
    $data = 1;
    $rdidStatus = '';
    if($rdid !=''){
        $rdidStatus = " and room_detail_id = '$rdid' ";
    }
    $sql = mysqli_query($conDB, "select status from inventory where add_date = '$date' and room_id = '$rid' $rdidStatus");
    if(mysqli_num_rows($sql)>0){
        $row = mysqli_fetch_assoc($sql);
        $data = $row['status'];
    }
    
    return $data;
}

function inventoryRoomUpdate($updateId, $room, $date,$status){
    global $conDB;
    $oneDay = strtotime('1 day 30 second', 0);
    $nxtDate = date('Y-m-d',strtotime($date) + $oneDay);
    $countTotalBooking = countTotalBooking($updateId, $date, $nxtDate);

    if($countTotalBooking > 0){
        $Bookroom = $countTotalBooking + $room;
        
    }else{
        $Bookroom = $room;
    }

    foreach (getRatePlanArrById($updateId) as $roomDetail) {
        $rdid = $roomDetail['id'];
        foreach(buildRatePlanView($updateId) as $roomList){

            $roomId = $roomList['id'];
            $rdid = $roomList['rdid'];
            
            $reExistQuery = mysqli_query($conDB, "select * from inventory where room_id='$roomId' and room_detail_id='$rdid' and add_date = '$date' ");
            if(mysqli_num_rows($reExistQuery) > 0){
                mysqli_query($conDB, "update inventory set room='$Bookroom',status='$status' where room_id='$updateId' and room_detail_id='$rdid' and add_date = '$date' ");
            }else{
                mysqli_query($conDB, "insert into inventory(room_id,room_detail_id,add_date,room,status) values('$roomId','$rdid','$date','$Bookroom','$status')");
            }

        }

    }

}

function inventoryRateUpdate($updateId, $updateDId, $price='',$price2='',$date, $child,$adult){
    global $conDB;
    $oneDay = strtotime('1 day 30 second', 0);

    if($price != ''){
        $priceUpade = "price='$price'";
    }

    if($price2 != ''){
        $priceUpade = "price2='$price2'";
    }
  
    $existQuery = mysqli_query($conDB, "select * from inventory where  room_id='$updateId' and room_detail_id='$updateDId'  and add_date = '$date'");
        if(mysqli_num_rows($existQuery) > 0){
            $sql= "update inventory set $priceUpade, eAdult='$adult', eChild='$child' where  room_id='$updateId' and room_detail_id='$updateDId' and add_date = '$date'";
            mysqli_query($conDB,$sql);
        }else{
            $sql= "insert into inventory(room_id,room_detail_id,add_date,price,price2,eAdult,eChild) values('$updateId','$updateDId','$date','$price','$price2','$adult','$child')";
            mysqli_query($conDB,$sql);
        }
    
    

}

function buildRatePlanView($rid){
    global $conDB;
    $sql = "SELECT room.*,room_detail.id as roomDetailID,room_detail.room_id FROM room, room_detail where room_detail.room_id = '$rid' and room.id = room_detail.room_id";
    $query = mysqli_query($conDB, $sql);
    $data = array();
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $data[]=[
                'id'=>$row['id'],
                'pId'=>$row['pId'],
                'adult'=>$row['noAdult'],
                'rdid'=>$row['roomDetailID'],
            ];
        }
    }

    return $data;
}


function roomExist($rid,$date='',$date2='',$rdid=''){
    global $conDB;
    $sql ="SELECT * FROM room where id = '$rid'";
    $status = mysqli_fetch_assoc(mysqli_query($conDB,$sql));
    $checkIn = $date;
    $checkOut = $date2;
    if($date == ''){
        $checkIn = $_SESSION['checkIn'];
    }
    
    if($date2 == ''){
        $checkOut = $_SESSION['checkout'];
    }
    
    if(getRoomLowPriceByIdWithDate($rid, $date) > settingValue()['advancePay']){
        $check_sold = countTotalQPBooking($rid, $checkIn);
    }else{
        $check_sold = countTotalBooking($rid, $checkIn);
    }
    
    $check_stock = getTotalRoom($rid, $checkIn);

    $result =  $check_stock - $check_sold;

    if($rdid != ''){
        if(isset($_SESSION['checkIn'])){
            $checkInTime = $_SESSION['checkIn'];
        }
    }

    
    if($result < 0){
        $result = 0;
    }

    return $result;
    
}

function settingValue(){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from setting where id = '1'");
    $row = mysqli_fetch_assoc($sql);
    return $row;
}

function countTotalBooking($rid, $date=''){
    global $conDB;
    $BookSql ="SELECT booking.id FROM bookingdetail,booking where booking.id = bookingdetail.bid and bookingdetail.roomId = '$rid' and booking.payment_status='complete' and bookingdetail.checkIn <= '$date' && bookingdetail.checkOut > '$date'";
                
    // $check_sold_arr = mysqli_fetch_assoc(mysqli_query($conDB,$BookSql));

    // $check_sold= $check_sold_arr['noRoom'];
    $check_sold= 1;
    return $check_sold;
}

function getTotalRoom($rid, $date,$date2=''){
    global $conDB;
    if($date2 == ''){
        $date2 = $date;
    }
    $room = 0;
    $query = "select room from inventory where room_id  = '$rid' and add_date = '$date'";
    $sql = mysqli_query($conDB, $query );
    if(mysqli_num_rows($sql)>0){
        while($inven_row = mysqli_fetch_assoc($sql)){
            $room=$inven_row['room'];
        }
    }else{
        $query = "select totalroom from room where id  = '$rid' and status = '1'";
        $sql = mysqli_query($conDB, $query);
        while($inven_row = mysqli_fetch_assoc($sql)){
            $room=$inven_row['totalroom'];
        }
    }
    
    return $room;
}

function countTotalQPBooking($rid, $date=''){
    global $conDB;
    $BookSql ="SELECT sum(nOfRoom) as noRoom FROM quickpay where  room = '$rid' and paymentStatus='complete' and checkIn <= '$date' && checkOut > '$date'";
                
    $check_sold_arr = mysqli_fetch_assoc(mysqli_query($conDB,$BookSql));

    $check_sold= $check_sold_arr['noRoom'];
    return $check_sold;
}

function getRatePlanByRoomId($rid){
    global $conDB;
    $data=array();
    $sql = mysqli_query($conDB, "select * from roomratetype where room_id  = '$rid'");
    if(mysqli_num_rows($sql)){
        while($row = mysqli_fetch_assoc($sql)){
            $data[]=$row;
        }
    }
    return $data;
}


function visiter_count($ip){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from counter_table where visiter_ip = '$ip'");
    if(mysqli_num_rows($sql)>0){

    }else{
        mysqli_query($conDB, "insert into counter_table(visiter_ip) values('$ip')");
    }
}

function getHotelDetail($slug = ''){
    global $conDB;
    $sql = "select * from hotel where id != ''";
    if($slug != ''){
        $sql .= " and slug = '$slug'";
    }
    $query = mysqli_query($conDB, $sql);
    $row = mysqli_fetch_assoc($query);
    return $row;
}

function hotelDetail(){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from profile");
    $row = mysqli_fetch_assoc($sql);
    $admin['email'] = $row['email'];
    $admin['primaryphone'] = $row['primaryphone'];
    $admin['address'] = $row['address'];
    $admin['pincode'] = $row['pincode'];
    $admin['district'] = $row['district'];
    $admin['gst'] = $row['gst'];
    $admin['description'] = $row['description'];
    $admin['name'] = $row['name'];
    $admin['logo'] = $row['logo'];
    $admin['url'] = $row['url'];
    $admin['checkIn'] = $row['checkIn'];
    $admin['checkOut'] = $row['checkOut'];
    return $admin;
}

function getImageById($rid){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from room_img where room_id = '$rid'");
    
    if(mysqli_num_rows($sql)){
        while($row = mysqli_fetch_assoc($sql)){
            $img[] = $row['image'];
        }
    }else{
        $img[] = 'room1.jpg';
    }
    
    return $img;
}

function getFacingDetailById($fid){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from facing where id = '$fid'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
        $data = $row;
    }

    return $data;
}

function getPackageArr(){
    global $conDB;
    $sql = mysqli_query($conDB, "select * from package where status = '1'");
    $data = array();
    if(mysqli_num_rows($sql) > 0){
        while($row = mysqli_fetch_assoc($sql)){
            $data[] = [
                'id'=> $row['id'],
                'slug'=> $row['slug'],
                'name'=> $row['name'],
                'img'=> $row['img'],
                'duration'=> $row['duration'],
                'description'=> $row['description'],
                'room'=> $row['room'],
                'discount'=> $row['discount'],
                'rdid'=> $row['rdid'],
            ];
        }
    }
    
    return $data;
}

function getRoomIdBySlug($slug){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select id from room where slug = '$slug'"));
    return $sql['id'];
}

function getDataBaseDate2($date){

    $checkInArr = explode('/',$date);
    $checkIn = $checkInArr['2'].'-'.$checkInArr['1'].'-'.$checkInArr['0'];
    return $checkIn;
}

function getAmenitieById($aid){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select title from amenities where id = '$aid'"));
    return $sql['title'];
}

function loopRoomExist($rid,$date='',$date2='',$rdid=''){
    
    if(roomExist($rid,$date,$date2,$rdid) > 0){
        $oneDay = strtotime('1 day 30 second', 0);
        
        $datediff = strtotime($date2) - strtotime($date);
        $output = round($datediff / (60 * 60 * 24));
        $data = 1;
        $countTotalBooking = array();
        for($i=1; $i<= $output; $i ++){
            $predate = date('Y-m-d',strtotime($date) + ($oneDay * $i) - $oneDay);
            // $nxtDate= date('Y-m-d',strtotime($date) + ($oneDay * $i));
            $countTotalBooking[] = roomExist($rid, $predate, $predate,$rdid);  
        }
        if(in_array('0' ,$countTotalBooking))    {
            $data = 0;
        } 
    }else{
        $data = 0;
    }
    return $data;
}

function roomMaxCapacityById($rid){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select * from room where id = '$rid'"));
    return $sql['roomcapacity'];
}

function getNightCountByDay($date1,$date2){
    $datetime1 = new DateTime($date1);
    $datetime2 = new DateTime($date2);
    $interval = $datetime1->diff($datetime2);
    return $interval->format('%a');
}

function SingleRoomPriceCalculator($rid, $rdid, $adult, $child , $nRoom='', $nNight, $roomPrice ='', $childPrice = '', $adultPrice='', $couponCode =''){
    global $conDB;

    $singleRoom = $roomPrice;
    $couponPrice = '';
    if($couponCode != ''){
        $couponPrice = couponActualPrice($couponCode,$roomPrice);
        $roomPrice = $roomPrice - $couponPrice;
    }
    
    $nightPrice = $roomPrice + $adultPrice + $childPrice;

    $totalRoomPrice = ($nightPrice  * $nNight) ;

    $gstper = getGSTPercentage($roomPrice);
    
    $gst = ($totalRoomPrice * $gstper) / 100;
    if($gstper == 0){
        $gst = 0;
    }

    $totalPrice = $totalRoomPrice + $gst;
    $nightPriceHtml = $nightPrice;
    if($nNight > 1){
        $nightPriceHtml = $nightPrice.' * '.$nNight;
    }


    $data = array();
    
    $data[]= [
        'room' => $singleRoom,
        'adultPrint' => $adult,
        'childPrint' => $child,
        'adult' => $adultPrice,
        'child' => $childPrice,
        'noNight' => $nNight,
        'night' => $nightPrice,
        'nightPrice' => $nightPriceHtml,
        'couponCode' => $couponCode,
        'couponPrice' => $couponPrice,
        'gstPer' => $gstper,
        'gst' => $gst,
        'total' => $totalPrice
        ];
    
    return $data;
}

function formatingDate($date){
    return  date("d-M-Y", strtotime($date));
}

function getRoomHeaderById($rid){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select header from room where id = '$rid'"));
    return $sql['header'];
}

function totalSessionPrice(){
    $price = 0;
    foreach($_SESSION['room'] as $key=>$val){
        $rdid = explode('-',$key)[0];
        
        $total_price = 0;
        $rid = $_SESSION['room'][$key]['roomId'];
        $child = $_SESSION['room'][$key]['child'];
        $adult = $_SESSION['room'][$key]['adult'];
        $checkInTime = $_SESSION['room'][$key]['checkIn'];
        $checkInOut = $_SESSION['room'][$key]['checkout'];
        $noAdult = $_SESSION['room'][$key]['adult'];
        $noRoom = $_SESSION['room'][$key]['room'];
        $night = $_SESSION['room'][$key]['night'];

        $percentage = settingValue()['PartialPaymentPrice'];

        if(roomExist($rid,$checkInTime) == 0){
            $obj->removeroom($key);
        }

        $roomPrice = getRoomPriceById($rid,$rdid, $adult, $checkInTime);
        $adultPrice = getAdultPriceByNoAdult($adult,$rid,$rdid, $checkInTime);
        $childPrice = getChildPriceByNoChild($child,$rid,$rdid, $checkInTime);
        

        if(isset($_SESSION['couponCode'])){
            $couponCode = $_SESSION['couponCode'];
        }else{
            $couponCode = '';
        }
        
        $nNight = getNightByTwoDates($checkInTime,$checkInOut);
        $singleRoomPriceCalculator = SingleRoomPriceCalculator($rid, $rdid, $adult, $child , $noRoom, $night, $roomPrice, $childPrice , $adultPrice, $couponCode);

        $price += $singleRoomPriceCalculator[0]['total'];
        $gst[$key]=$singleRoomPriceCalculator[0]['gst'];
        $nightPrint[$key]=$singleRoomPriceCalculator[0]['nightPrice'];
        $noNight[$key]=$singleRoomPriceCalculator[0]['noNight'];
        $shortDate[$key]=getDateFormatByTwoDate($_SESSION['room'][$key]['checkIn'],$_SESSION['room'][$key]['checkout']);
        $total[$key]=$singleRoomPriceCalculator[0]['total'];
    }


    $_SESSION['gossCharge'] = $price;
    $_SESSION['roomTotalPrice'] = $price;

    if(isset($_SESSION['pickUp']) && $_SESSION['pickUp'] != ''){
        $pickup = $_SESSION['pickUp'];
        $price += $pickup;
        $_SESSION['roomTotalPrice'] = $price;
    }
    
    if(isset($_SESSION['partial']) && $_SESSION['partial'] == 'Yes'){
        $percentage = settingValue()['PartialPaymentPrice']; 
        $price = $price * $percentage / 100;
        $_SESSION['roomTotalPrice'] = $price;
    }

    $data=[
        'gst'=>$gst,
        'night'=>$nightPrint,
        'price'=>$price,
        'noNight'=>$noNight,
        'shortDateUpdate'=>$shortDate,
        'total'=>$total,
    ];

    
    
    return $data;
}

function calculateTotalBookingPrice(){
    $price = $_SESSION['gossCharge'];
    $result = $price;
    
    
    if(isset($_SESSION['pickUp']) && $_SESSION['pickUp'] != ''){
        $pickup = $_SESSION['pickUp'];
        $result += $pickup;
    }
    
    if(isset($_SESSION['partial']) && $_SESSION['partial'] == 'Yes'){
        $percentage = settingValue()['PartialPaymentPrice']; 
        $result = $result * $percentage / 100;
    }
    
    // $_SESSION['roomTotalPrice'] = $result;
    
    return $result;
}

function getBookingNumber(){
    global $conDB;
    
    $oid = BOOK_GENERATE.unique_id(6);

    return $oid;
}

function checkLive(){
    global $conDB;
    $sql = mysqli_fetch_assoc(mysqli_query($conDB, "select * from live where id = '1'"));
    return $sql['status'];
}



?>