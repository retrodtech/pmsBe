<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'add_to_room.php');
$obj = new add_to_room();
$hotelId = $_POST['hotelId'];
$currentDate = strtotime(date('Y-m-d'));
$check_in_date = $_POST['check_in_date'];
$check_out_date = $_POST['check_out_date'];



$checkInArr = explode('/',$check_in_date);
$checkIn = $checkInArr['2'].'-'.$checkInArr['1'].'-'.$checkInArr['0'];

$checkOutArr = explode('/',$check_out_date);

$oneDay = strtotime('1 day 30 second', 0);

$checkOutDate = $checkOutArr['2'].'-'.$checkOutArr['1'].'-'.$checkOutArr['0'];

if($check_in_date == $check_out_date){
    $checkOut = date('Y-m-d', strtotime($checkOutDate) + $oneDay);
}else{
    $checkOut = $checkOutDate;
}
echo '<div class="col-md-12 col-sm-12 col-xs-12">';
if(strtotime($checkIn) >= $currentDate){
    $_SESSION['checkIn'] = $checkIn;
    $_SESSION['checkout'] = $checkOut;

    $earlier = new DateTime($_SESSION['checkIn']);
    $later = new DateTime($_SESSION['checkout']);

    $night_count = $later->diff($earlier)->format("%a");

    $_SESSION['night_stay'] = $night_count;

    $sql = mysqli_query($conDB, "select * from room where hotelId = '$hotelId'");
    if(mysqli_num_rows($sql) > 0){
        $count = 0;
        while($room_rows = mysqli_fetch_assoc($sql)){ 
            $count ++;
            $room_id = $room_rows['id'];
            $mrp = $room_rows['mrp'];
            if($mrp != 0 ){
                $lowstPrice = $mrp - getRoomLowPriceByIdWithDate($room_id,  $_SESSION['checkIn']);
                $mrpPercentage = intval(($lowstPrice /  $mrp) * 100);
            }
            ?>
            

                <div class="listroBox">
                    <div class="row">
                        <div class="col-md-6">
                            <figure>
                                <?php
                                    $getImageById = getImageById($room_id);
                                    $img_count = 0;
                                    $totalImg=count($getImageById);
                                    foreach($getImageById as $key=>$val){
                                        $img_count ++;
                                        $img = $getImageById[$key];
                                        $imgSrc = FRONT_SITE_ROOM_IMG.$img;
                                        if($img_count == 1){
                                            echo "<div class='big' id='bigImgContent$count'><img class='bigImgContent' id='bigImg' src='$imgSrc' class='img-fluid' alt='' style='height: 100%;width: auto;'></div>";
                                            echo "<div class='small'>";
                                            echo "<img data-id='bigImgContent$count' class='smallImg' src='$imgSrc' class='img-fluid' alt=''>";
                                        }elseif($img_count == $totalImg) {
                                            echo "<img data-id='bigImgContent$count' class='smallImg' src='$imgSrc' class='img-fluid' alt=''>";
                                            echo "</div>";
                                        }
                                        else{
                                            echo "<img data-id='bigImgContent$count' class='smallImg' src='$imgSrc' class='img-fluid' alt=''>";
                                        }
                                    }
                                
                                ?>
                            </figure>
                        </div>
                        <div class="col-md-6">
                            <div class="listroBoxmain">
                                <h3><?php echo $room_rows['header'] ?></h3>
                                <?php
                                        
                                            if($mrp != 0 ){
                                                echo "<div><b>M.R.P.: </b><span style='text-decoration: line-through;'>Rs   $mrp</span> <span>($mrpPercentage % Off)</span></div>";
                                            }
                                        
                                        ?>
                                <ul>
                                    <li>
                                        <div>Bed type: <?php echo  $room_rows['bedtype']?></div>
                                        <div>Max Room capacity: <?php echo  $room_rows['roomcapacity']?></div>
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
                                    
                                        $am_sql = mysqli_query($conDB, "select * from room_amenities where room_id = '{$room_rows['id']}'");
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

                    <?php
                    
                        $detail_sql = mysqli_query($conDB, "select * from roomratetype where room_id = '{$room_rows['id']}'");
                        $roomDetailId = $room_rows['id'];
                        if(mysqli_num_rows($detail_sql)>0){
                            while($detail_row= mysqli_fetch_assoc($detail_sql)){ 
                                $checkIn = $_SESSION['checkIn'];
                                
                                ?>
                                <ul class="roomAddContent">
                                    <li>
                                        <h3><?php echo ucfirst($detail_row['title']) ?></h3>
                                    </li>
                                    <li >
                                        <h4>Rooms|Guests</h4>
                                        <span class="room-guest-details">1 Room(s) <?php echo getRoomAdultCountById($room_id) ?> Adults, <?php echo getRoomChildCountById($room_id) ?> Kids</span>
                                    </li>
                                    <?php
                                    $roomPrice = getRoomPriceById($room_id,$detail_row['id'],getRoomAdultCountById($room_id), $checkIn);
                                    $advancePayPrice = settingValue()['advancePay'];
                                    if($roomPrice >= $advancePayPrice){
                                        echo  "
                                        
                                        ";
                                    }else{
                                        echo  "
                                        
                                        <li>
                                            <b>Rs $roomPrice </b> <br/>
                                            <span>per room / <br/> night Excluding <br/> GST</span>
                                        </li>
                                    
                                    ";
                                    }
                                    
                                    ?>
                                    
                                    <li>
                                    <?php
                                    
                                    
                                            
                                            if(loopRoomExist($room_id,$_SESSION['checkIn'],$_SESSION['checkout'],$detail_row['id']) > 0){
                                               
                                                
                                                if($roomPrice >= $advancePayPrice){
                                                    $advanceUrl = FRONT_BOOKING_SITE.'/quick-pay';
                                                    echo  "
                                                    
                                                        <div class='flex'>
                                                            <a class='btn btn-outline-secondary' target='_blank' style='text-align:center;margin-right: 10px;' href='https://jamindarspalace.com/contact.php'>Contact Us</a>
                                                            <a class='btn btn-info' style='text-align:center' target='_blank' href='$advanceUrl'>Pay Advance</a>
                                                        </div>
                                                    
                                                    ";
                                                }else{
                                                    echo "<a  class='button bg-gradient-info add_guest_btn' data-id='{$detail_row['id']}' data-room=' $room_id '>Book Room</a>";
                                                }

                                            }else{
                                                echo "<a class='button bg-gradient-danger '>Not Available</a>";
                                            }
                                            
                                            
                                        ?>
                                        
                                    </li>
                                </ul>
                                <div class="add_room_detail add_room_detail<?php echo $detail_row['id'] ?>"></div>
                            
                        <?php }
                        }
                        echo '</div>';
                    }
                }
                
}else{
    echo 'Date is not found';
}
echo '</div>';
?>
