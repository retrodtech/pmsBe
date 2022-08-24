<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$type = '';

if(isset($_POST['type'])){
    $type = $_POST['type'];
}

if($type == 'loadStayView'){

    $date = $_POST['date'];
    $thHtml = '<th></th>';
    
    $tdForRT = '';
    $checkInStatusListHtml = '<ul>';
    foreach(checkGuestCheckInStatus() as $checkInStatusList){
        $name = $checkInStatusList['name'];
        $clr = $checkInStatusList['clr'];
        $checkInStatusListHtml .= "<li><span>$name</span><span style='background:#$clr' class='clrRev'></span></li>";
    }

    $checkInStatusListHtml .= "<ul>";
    

    for ($i=-2; $i < 10; $i++) { 
        $oneDate = date("Y-m-d", strtotime(date('Y-m-d')) + (86400 * $i));
        $formatDate = date('M-d', strtotime($oneDate));
        $thHtml .= "<th id='$formatDate'>$formatDate</th>";
    }

    $hdHtmlRow = "<tr>$thHtml</tr>";

    foreach(getRoomType() as $roomTypeList){
        $roomTypeId = $roomTypeList['id'];
        $roomTypeName = $roomTypeList['header'];
        $roomTypeHdHtml = '';
        for ($i=-2; $i < 10; $i++) { 
            $roomTypeHdHtml .= "<td></td>";
        };

        $tdForRT .= "<tr>
            <td class='remove-hover' style='z-index: 5;position: relative;'>$roomTypeName</td> $roomTypeHdHtml
        </tr>";


        foreach(getRoomNumber('','',$roomTypeId) as $roomNumList){
            $roomNumId = $roomNumList['id'];
            $roomNum = $roomNumList['roomNo'];
            $hdHtml = '';
            
            for ($i=-2; $i < 10; $i++) { 

                $oneDate = date("Y-m-d", strtotime(date('Y-m-d')) + (86400 * $i));
                $formatDate = date('M-d', strtotime($oneDate));
                $bid = '';
                $bookPersion = '';
                $nightCount = '';
                $btn = '';
                $checkInStatusClr = '';
                

                if(isset(getBookingData('',$roomNum,$oneDate,'','onlyCheckIn')[0]['bid'])){
                    $bookinDetailArry = getBookingData('',$roomNum,$oneDate,'','onlyCheckIn')[0];
                    $bid = $bookinDetailArry['bid'];
                    
                    $checkIn = $bookinDetailArry['checkIn'];
                    $checkOut = $bookinDetailArry['checkOut'];
                    $nightCount = getNightByTwoDates($checkIn,$checkOut);

                    $checkInStatusAray = checkGuestCheckInStatus($bookinDetailArry['checkinstatus'])[0];

                    $checkInStatus = $checkInStatusAray['name'];
                    $checkInStatusClr = $checkInStatusAray['clr'];
                    

                    if(isset(getGuestDetail($bid,1)[0]['name'])){
                        $bookPersion = ucfirst(getGuestDetail($bid,1)[0]['name']);
                        $btn = 'badge guestOnStayView';
                    }
    
                }

                $hdHtml .= "<td  class='$formatDate'><span data-bid='$bid' class='$btn' style='width: calc(100% * $nightCount); background: #$checkInStatusClr'>$bookPersion</span></td>";

            }

            $tdForRT .= "<tr class='roomNum'><td>$roomNum</td>$hdHtml</tr>";
        }


    }

    

    $html = "<div class='card'>
                <div class='card-head'><div class='reverenceClr'>$checkInStatusListHtml</div></div>
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table class='table align-items-center mb-0 tableLine vertical-scroll'>
                            <thead>$hdHtmlRow</thead>
                            <tbody>$tdForRT</tbody>
                        </table>
                    </div>
                </div>
                
            </div>";

    
    echo $html;
}



?>