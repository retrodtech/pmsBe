<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'add_to_room.php');
$obj = new add_to_room();

$type = $_POST['type'];
$one_day = strtotime('1 day 00 second', 0);

if($type == 'addDate'){
    $dateArr = explode('/',$_POST['date']);
    $date = $dateArr['2'].'-'.$dateArr['1'].'-'.$dateArr['0'];
  
    $_SESSION['checkIn'] = $date;
    $_SESSION['checkout'] = date('Y-m-d',strtotime($date) + $one_day);

}

if($type == 'loadRoom'){ 
   
    $slug = $_POST['id'];
    $sql = mysqli_query($conDB, "select * from room where slug = '$slug'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);

        $room_id = $row['id'];
        $id = $row['id'];
        $header = $row['header'];
        $bedtype = $row['bedtype'];
        $roomcapacity = $row['roomcapacity'];
        $capacity = $row['noAdult'];
        $mrp = $row['mrp'];
        $roomLowPrice = getRoomLowPriceByIdWithDate($room_id,  $_SESSION['checkIn']);
        if($mrp != 0 ){
            $lowstPrice = $mrp - $roomLowPrice;
            $mrpPercentage = intval(($lowstPrice /  $mrp) * 100);
        }
    }
    
    ?>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="listroBox">
                <div class="row">
                    <div class="col-md-6">
                        <figure class="roomDetail">
                            <?php
                                $getImageById = getImageById($room_id);
                        
                                $img_count = 0;
                                $totalImg=count($getImageById);
                                foreach($getImageById as $key=>$val){
                                    $img_count ++;
                                    $img = $getImageById[$key];
                                    $imgSrc = WS_FRONT_SITE_IMG.'room/'.$img;
                                    if($img_count == 1){
                                        echo "<div id='bigImgContent$id' class='big'><img id='bigImg' class='bigImgContent' src='$imgSrc' class='img-fluid' alt='' style='height: 100%;width: auto;'></div>";
                                        echo "<div class='small owl-carousel' style='display: block;'>";
                                        echo "<img class='smallImg' data-id='bigImgContent$id' src='$imgSrc' class='img-fluid' alt='$header Image'>";
                                    }elseif($img_count == $totalImg) {
                                        echo "<img class='smallImg' data-id='bigImgContent$id' src='$imgSrc' class='img-fluid' alt='$header Image'>";
                                        echo "</div>";
                                    }
                                    else{
                                        echo "<img class='smallImg' data-id='bigImgContent$id' src='$imgSrc' class='img-fluid' alt='$header Image'>";
                                    }
                                }
                            
                            ?>
                        </figure>
                    </div>
                    <div class="col-md-6">
                        <div class="listroBoxmain">
                            <div>
                                <h3><?php echo $header ?></h3>
                                <?php
                                
                                    if($mrp != 0 ){
                                        echo "<div class='priceTag'><b>Rack Rate: </b><span class='rackRatePrint' style='text-decoration: line-through;'>Rs   $mrp</span> <span class='discountBar'><div>$mrpPercentage % Off</div><span class='bar'></span></span></div>";
                                    }
                                
                                ?>
                            </div>
                            <ul>
                                <li>
                                    <div>Bed type: <b><?php echo  $bedtype?></b></div>
                                    <div>Room capacity: <b><?php echo  $capacity?></b></div>
                                </li>

                                <li><div class="R_retings">
                                        <div class="list-rat-ch list-room-rati"> <i class="fa fa-star"
                                                aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i
                                                class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star"
                                                aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> </div>
                                    </div>
                                </li>
                            </ul>
                            <ul class="amenitie_list">
                                <?php
                                
                                    $am_sql = mysqli_query($conDB, "select * from room_amenities where room_id = '{$room_id}'");
                                    if(mysqli_num_rows($am_sql)>0){
                                        while($am_row= mysqli_fetch_assoc($am_sql)){
                                            $ame = getAmenitieById($am_row['amenitie_id']);
                                            echo "<li> {$ame} </li>";
                                        }
                                    }
                                
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <br/>
                <?php
                                
                    $detail_sql = mysqli_query($conDB, "select * from roomratetype where room_id = '{$room_id}'");
                    
                    if(mysqli_num_rows($detail_sql)>0){
                        while($detail_row= mysqli_fetch_assoc($detail_sql)){
                            
                                
                                if(isset($_SESSION['room']) && !empty($_SESSION['room'])){
                                    $fistKey = array_keys($_SESSION['room'])[0];
                                    $checkInTime = $_SESSION['room'][$fistKey]['checkIn'];
                                  
                                }else{
                                    $checkInTime = $_SESSION['checkIn'];
                                }
                            
                            ?>
                            <ul class="roomAddContent">
                            <li class="add_room_detail add_room_detail<?php echo $detail_row['id'] ?>" style="display: flex;justify-content: flex-end;width: 100%;">

                            </li>
                                <li>
                                    <h3 style="font-size: 24px;"><?php echo ucfirst($detail_row['title']) ?></h3>
                                </li>
                                <li>
                                    <h4 style="font-size: 16px;margin-bottom: 6px;font-weight: 700;">Rooms|Guests</h4>
                                    <span style="background:#e5e5e5;" class="room-guest-details" style="font-size: 12px;font-weight: 600;">1 Room(s) <?php echo getMinRoomAdultCountById($room_id) ?> Adults, <?php echo getRoomChildCountById($room_id) ?> Childs</span>
                                </li>
                                <li>
                                    <b style="font-weight: 700;font-size: 20px;color: #000;">Rs <span style="color: #ff1100;"><?php echo getRoomPriceById($room_id,$detail_row['id'], getMinRoomAdultCountById($room_id), $checkInTime) ?></span></b> <br/>
                                    <span style="font-size: 12px;font-weight: 600;">Per room / <br/> night Excluding <br/> GST</span>
                                </li>
                                <li>
                                    <?php
                                   
                                    if(loopRoomExist($room_id,$_SESSION['checkIn'],$_SESSION['checkout'],$detail_row['id']) > 0){
                                        if($roomLowPrice > settingValue()['advancePay'] ){
                                            $advanceUrl = FRONT_BOOKING_SITE.'/quick-pay';
                                            echo "<a  class='button bg-gradient-info' target='_blank' href='$advanceUrl'>Pay Advance</a>";
                                        }else{
                                            echo "<a  class='button bg-gradient-info add_guest_btn' data-id='{$detail_row['id']}' data-room=' $room_id '>Book Room</a>";
                                        }
                                    }else{
                                        echo "<a class='button bg-gradient-danger '>Not Available</a>";
                                    }
                                    
                                    
                                    ?>
                                    
                                </li>
                                
                            </ul>
                    <?php }
                    }
                
                ?>

            </div>

            </div>
            
            
            
        

    </div> 
<?php }



if($type == 'loadInputDate'){
 
    
    $data = date('d/m/Y',strtotime($_SESSION['checkIn']));

    echo $data;
}

if($type == 'checkDateAvailableOrNot'){
    $date = $_POST['date'];
    $rdid = $_POST['rdid'];
    $time = strtotime($date);
    $pretime = $time ;
    for($i=0; $i<=120;$i++){ 
        $present_day = $pretime + ($i * $one_day);
        $next_day = $pretime + ($i * $one_day) - $one_day;
        if(roomExist($rdid,date('Y-m-d',$next_day),date('Y-m-d',$present_day) ) == 0){
            $pday = date('Y-m-d',$next_day);
            $blockDate[] = $pday;
        }
    } 
    echo 50;
}

if($type == 'loadRoomDataSlide'){
  
    $time = strtotime(getDataBaseDate2($_POST['date']));
   
    $pretime = $time ;
    $data = '<div class="slideshow-container">';
    $rdid = $_POST['rdid'];
    for($i=1; $i<=10;$i++){ 
        $present_day = $pretime + ($i * $one_day);
        $next_day = $pretime + ($i * $one_day) - $one_day;
        $checkRoomPresent = '';
        $printDate = date('Y-m-d',$next_day);
        
        $roomPrice = getRoomLowPriceByIdWithDate($rdid, $printDate);

            if(roomExist($rdid,date('Y-m-d',$next_day),date('Y-m-d',$present_day) ) > 0){
                $addClass = 'available';
                $checkRoomPresent = '<span style="text-align: center;display: block;padding: 10px 0 0;color: #27bb2d;font-size: 11px;font-weight: 700;">Available</span>';
            }else{
                $addClass = 'soldOut';
                $checkRoomPresent = '<span style="text-align: center;display: block;padding: 10px 0 0;color: red;font-size: 11px;font-weight: 700;">Sold Out</span>';
            }
            
            $data .= '
                
        
                    <div class="mySlides fadeAni">
                        <div style="cursor: pointer;" data-date="'.$printDate.'" class="CheckNight" data-rid="'.$rdid.'" >
                            <div class="slide active '.$addClass.' ">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>
                                            <p class="datefld">'.date('d-M, y', strtotime($printDate)).'</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="range">Starts From <span class="pricetxt">Rs. 
                                        
                                        '.$roomPrice.'
                                        
                                        
                                        </span></span> 
                                        <br/>'.$checkRoomPresent.'
                                    
                                    </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                
    
        ';
        
        }

        $data .= '
        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>
        </div>';

    

        echo $data;
    
    
}

if($type == 'loadCheckOutDate'){
    $date = $_SESSION['checkout'];
    echo date('d/m/Y', strtotime($date));
}

if($type == 'checkOutDate'){
    $date = $_POST['date'];
    $time = strtotime(getDataBaseDate2($date));
    $_SESSION['checkout'] = date('Y-m-d', $time);
} 




if($type == 'roomCheckoutDateUpdate'){
    $key = $_POST['key'];
    $rdid = explode('-',$key)[0];
    $getDate = $_SESSION['room'][$key]['checkout'];
    $checkOut =  date('d-M-Y', strtotime($getDate));
    $roomPrice = getRoomPriceById($_SESSION['room'][$key]['roomId'],$rdid, $_SESSION['room'][$key]['adult'],$_SESSION['room'][$key]['checkIn']);

    $nightPrint = totalSessionPrice()['night'][$key];
    $gst = totalSessionPrice()['gst'][$key];
    $noNight = totalSessionPrice()['noNight'][$key];
    $shortDateUpdate = totalSessionPrice()['shortDateUpdate'][$key];
    $total = totalSessionPrice()['total'][$key];

    $data = [
        'night'=>$nightPrint,
        'gst'=>$gst,
        'checkOut'=>$checkOut,
        'noNight'=>$noNight,
        'shortDateUpdate'=>$shortDateUpdate,
        'total'=>$total,
    ];

    echo json_encode($data);
}
 

 


?>