<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'add_to_room.php');
$obj = new add_to_room();

$type = $_POST['type'];


if($type == 'todayCheckIn'){
    $current_date = strtotime(date('Y-m-d'));
    $today = date('Y-m-d', $current_date);
    
    $sql = mysqli_query($conDB, "select booking.*,bookingdetail.checkIn,bookingdetail.checkout from booking,bookingdetail where booking.id = bookingdetail.bid and bookingdetail.checkIn = '$today' and booking.payment_status = 'complete' GROUP by booking.id");
    $data = '
        <table border="1" style="margin: 30px 0;">
        
    ';
    $count = 0;
    if(mysqli_num_rows($sql) > 0){
        
        while($row = mysqli_fetch_assoc($sql)){
            $count ++;
            if($count == 1){
                $data .='<tr><td></td> <td> <a href="download.php?id=todayCheckInDownload"> <i class="fa fa-download"></i> </a></td></tr>';
            }
            $data .= '
                
                <tr>
                    <td style="text-align:left">
                        <div style="font-weight: 700;">'.ucfirst($row["name"]).'</div>
                        <div>'.$row["bookinId"].'</div>
                    </td>
                    <td style="text-align:right">
                        <div>'.getDateFormatByTwoDate($row["checkIn"],$row["checkout"]).'</div>
                        <div><small>₹ '.$row["grossCharge"].'</small> / <strong>₹ '.$row["userPay"].'</strong></div>
                    </td>
                </tr>
            ';
        }
    }else{
        $data .= '
                <tr>
                    <td style="text-align:left">
                        No Data
                    </td>
                </tr>
            ';
    }
    $data .= '</table>';

    echo $data;
}

if($type == 'todayCheckOut'){
    $current_date = strtotime(date('Y-m-d'));
    $today = date('Y-m-d', $current_date);
    
    $sql = mysqli_query($conDB, "select booking.*,bookingdetail.checkIn,bookingdetail.checkout from booking,bookingdetail where booking.id = bookingdetail.bid and bookingdetail.checkout = '$today' and booking.payment_status = 'complete' GROUP by booking.id");
    $data = '
        <table border="1" style="margin: 30px 0;">
        
    ';
    $count = 0;
    if(mysqli_num_rows($sql) > 0){
        while($row = mysqli_fetch_assoc($sql)){
            $count ++;
            if($count == 1){
                $data .='<tr><td></td> <td> <a href="download.php?id=todayCheckOutDownload"> <i class="fa fa-download"></i> </a></td></tr>';
            }
            $data .= '
                <tr>
                    <td style="text-align:left">
                        <div style="font-weight: 700;">'.ucfirst($row["name"]).'</div>
                        <div>'.$row["bookinId"].'</div>
                    </td>
                    <td style="text-align:right">
                        <div>'.getDateFormatByTwoDate($row["checkIn"],$row["checkout"]).'</div>
                        <div><small>₹ '.$row["grossCharge"].'</small> / <strong>₹ '.$row["userPay"].'</strong></div>
                    </td>
                </tr>
            ';
        }
    }else{
        $data .= '
                <tr>
                    <td style="text-align:left">
                        No Data
                    </td>
                </tr>
            ';
    }
    $data .= '</table>';

    echo $data;
}

if($type == 'qptodayCheckIn'){
    $current_date = strtotime(date('Y-m-d'));
    $today = date('Y-m-d', $current_date);
    
    $sql = mysqli_query($conDB, "select* from quickpay where checkIn = '$today' and paymentStatus = 'complete'");
    $data = '
        
        <table border="1" style="margin: 30px 0;">
        
    ';
    $count = 0;
    if(mysqli_num_rows($sql) > 0){
        while($row = mysqli_fetch_assoc($sql)){
            $count ++;
            if($count == 1){
                $data .='<tr><td></td> <td> <a href="download.php?id=todayQpCheckInDownload"> <i class="fa fa-download"></i> </a></td></tr>';
            }
            $data .= '
            
                <tr>
                    <td style="text-align:left">
                        <div style="font-weight: 700;">'.ucfirst($row["name"]).'</div>
                        <div>'.$row["orderId"].'</div>
                    </td>
                    <td style="text-align:right">
                        <div>'.getDateFormatByTwoDate($row["checkIn"],$row["checkOut"]).'</div>
                        <div><small>₹ '.$row["totalAmount"].'</small> / <strong>₹ '.$row["amount"].'</strong></div>
                    </td>
                </tr>
            ';
        }
    }else{
        $data .= '
                <tr>
                    <td style="text-align:left">
                        No Data
                    </td>
                </tr>
            ';
    }
    $data .= '</table>';

    echo $data;
}

if($type == 'qptodayCheckOut'){
    $current_date = strtotime(date('Y-m-d'));
    $today = date('Y-m-d', $current_date);
    
    $sql = mysqli_query($conDB, "select* from quickpay where checkOut = '$today' and paymentStatus = 'complete'");
    $data = '
        <table border="1" style="margin: 30px 0;">
        
    ';
    $count = 0;
    if(mysqli_num_rows($sql) > 0){
        while($row = mysqli_fetch_assoc($sql)){
            $count ++;
            if($count == 1){
                $data .='<tr><td></td> <td> <a href="download.php?id=todayQpCheckOutDownload"> <i class="fa fa-download"></i> </a></td></tr>';
            }
            $data .= '
                    <tr>
                        <td style="text-align:left">
                            <div style="font-weight: 700;">'.ucfirst($row["name"]).'</div>
                            <div>'.$row["orderId"].'</div>
                        </td>
                        <td style="text-align:right">
                            <div>'.getDateFormatByTwoDate($row["checkIn"],$row["checkOut"]).'</div>
                            <div><small>₹ '.$row["totalAmount"].'</small> / <strong>₹ '.$row["amount"].'</strong></div>
                        </td>
                    </tr>
            ';
        }
    }else{
        $data .= '
                <tr>
                    <td style="text-align:left">
                        No Data
                    </td>
                </tr>
            ';
    }
    $data .= '</table>';

    echo $data;
}

if($type == 'nightChange'){
    $rid = $_POST['rid'];
    $date = $_POST['date'];

    $current_date = strtotime(date('Y-m-d'));
    $one_day = strtotime('1 day 00 second', 0);
    $datearr = explode("-",$date);
    $month = $datearr[1];
    $day = $datearr[2];
    $year = $datearr[0];
    
    
    if(checkdate($month, $day, $year)){
        if($current_date <= strtotime($date)){
            $obj->checkInDateUpdate($date, date('Y-m-d', strtotime($date) + $one_day));
        }else{
             $current_date = strtotime(date('Y-m-d'));
             $obj->checkInDateUpdate(date('Y-m-d',$current_date), date('Y-m-d',$current_date + $one_day));
        }
            
    }else{
            $current_date = strtotime(date('Y-m-d'));
            $obj->checkInDateUpdate(date('Y-m-d',$current_date ), date('Y-m-d',$current_date + $one_day ));
    }
}

if($type == 'convertArryToJSON'){
    $arry = $_POST['array'];

    echo convertArryToJSON($arry);

}



?>