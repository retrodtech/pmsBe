<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$type = '';

if(isset($_POST['type'])){
    $type = $_POST['type'];
}
if(!empty($_POST['datepicker'])){
    // $current_date = strtotime(date('y-m-d'));
    $date = $_POST['datepicker'];
    $dateArr = explode('/',$date);
    $dateStr = $dateArr['2'].-$dateArr['0'].-$dateArr['1'];
    $current_date = strtotime($dateStr);

}else{
    $current_date = strtotime(date('y-m-d'));
}
$oneDay = strtotime('1 day 30 second', 0);




if($_POST['inventoryAction'] == 'rate'){
    
?>

<ul class="accordion">
    <div class="table-responsive">
    <table class="table align-items-center mb-0 tableLine">
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sl.</th>
            <th width="200" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rate Plan</th>
            <?php
            
                $oneDay = strtotime('1 day 30 second', 0);

                for($i=1;$i<=10;$i++){
                    $day = $current_date + ($i * $oneDay) - $oneDay;
                    $inDay = date('y-m-d', $day);
                    $inDay = date('d-M', $day);

                    echo "
                        <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>$inDay</th>
                    ";
                }

            ?>
        </tr>
        </table> </div>
        
        <?php
            $si = 0;
            $sql = mysqli_query($conDB, "select * from room ");
            $rowCount = 0;
            if(mysqli_num_rows($sql)>0){
                while($row = mysqli_fetch_assoc($sql)){
                    $rowCount ++;
                    $room_id = $row['id'];
                    $si++; 
                    $getRatePlanByRoomId = getRatePlanByRoomId($room_id);
                   if($rowCount == 1){
                       $show = 'show';
                       $display = 'style="display: block"';
                   }else{
                    $show = '';
                    $display = '';
                   }; ?>

                   
                   <li>
                       <a class="toggle" href=#><?php echo $si." ". $row['header'] ?></a>
                       <div class="inner <?php echo $show ?>" <?php echo $display ?>> 
                       <div class="table-responsive">
                        <table class="table align-items-center mb-0 tableLine">
                       <?php 
                       $sl2 =0;
                    //    pr($getRatePlanByRoomId);
                       foreach($getRatePlanByRoomId as $key=>$val){
                           $sl2++;
                            $rdid = $getRatePlanByRoomId[$key]['id'];
                            
                            ?>
                            <tr>
                                <td class="center">
                                   <b> <?php echo $sl2 ?></b>
                                    
                                </td>
                                <td width="200">
                                    <span class="db" style="margin-bottom: 5px;"><?php echo $getRatePlanByRoomId[$key]['title'] ?></span>
                                    <span class="tableHoverShow">
                                        <img class="rate_update in_btn edit btn bg-gradient-success dib mr8" data-id="<?php echo $rdid ?>" data-rid="<?php echo $room_id ?>" src="<?php echo FRONT_SITE_IMG.'/icon/edit.png' ?>" alt="">
                                        <!-- <img class="reload_rate in_btn remove" data-id="<?php echo $rdid ?>" data-rid="<?php echo $room_id ?>" src="<?php echo FRONT_SITE_IMG.'/icon/reload.png' ?>" alt=""> -->
                                    </span>
                                </td>
                                <?php
                                
                                

                                
                                for($i=1;$i<=10;$i++){
                                    $day = $current_date + ($i * $oneDay) - $oneDay;
                                    $active = 1;
                                    
                                    // $price = getRoomPriceById($rdid,date('y-m-d',$day),date('y-m-d',$day));
                                    $rateData2 = '';
                                    $statusCheck = inventoryCheck(date('Y-m-d',$day));
                                    $dateCheck = date('Y-m-d',$day);
                                    if($statusCheck == 0){
                                        $statusClass = 'deactivate';
                                    }else{
                                        $statusClass = 'activate';
                                    }
                                    
                                    $id = $val['id'];
                                    $date = date('Y-m-d',$day);
                                    if($val['singlePrice'] != 0){
                                        $price = getRoomPriceById($room_id,$rdid,1,$date);
                                        $rateData = "<span><input data-date='$dateCheck' data-rid='$room_id' data-rdid='$rdid' data-adult='1' type='text' value='$price' class='inlineRoomPrice db'></span>"; 
                                    }
                                    if($val['doublePrice'] != 0){
                                        $price = getRoomPriceById($room_id,$rdid,2,$date);
                                        $rateData2 = " <span><input data-date='$dateCheck' data-rid='$room_id' data-rdid='$rdid' data-adult='2' type='text' value='$price' class='inlineRoomPrice db'></span>"; 
                                    }

                                    echo "
                                        <td class='center $statusClass'>
                                            $rateData
                                            $rateData2
                                        </td>
                                    ";
                                } 
                            
                                
                                ?>

                            </tr>

                    <?php  } ?> </table> </div>
                       </div>
                       </p>
                   </li> 
                <?php

                }

            }
        ?>
   

    </ul>


<?php }else{ ?>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center mb-0 tableLine">
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sl.</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Room</th>
                    <?php
                    

                        for($i=1;$i<=10;$i++){
                            
                            $day = $current_date + ($i * $oneDay) - $oneDay;
                            $inDay = date('d-M', $day);
                            $statusCheck = inventoryCheck(date('Y-m-d',$day));
                            
                            
                            
                            if($statusCheck == 0){
                                $statusClass = 'deactivate';
                            }else{
                                $statusClass = 'activate';
                            }
                            echo "
                                <th class='$statusClass text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>$inDay</th>
                            ";
                        }

                    ?>
                </tr>
                <?php
                    $si = 0;
                    $sql = mysqli_query($conDB, "select * from room ");
                    if(mysqli_num_rows($sql)>0){
                        while($row = mysqli_fetch_assoc($sql)){
                            $room_id = $row['id'];
                            $si++; ?>
                                <tr>
                                <td class="mb-0 text-xs">
                                    <span><b><?php echo $si ?></b></span>
                                    
                                </td>
                                <td class="mb-0 text-xs">
                                    <span class="db bold"><?php echo $row['header'] ?></span> <br/> 
                                    <span class="tableHoverShow">
                                        <img class="room_update in_btn edit btn bg-gradient-success dib mr8" data-id="<?php echo $room_id ?>" src="<?php echo FRONT_SITE_IMG.'icon/edit.png' ?>" alt="">
                                        <!-- <img class="room_reload in_btn remove" data-id="<?php echo $room_id ?>" src="<?php echo FRONT_SITE_IMG.'icon/reload.png' ?>" alt=""> -->
                                        <img class="room_block in_btn remove btn bg-gradient-danger " data-id="<?php echo $room_id ?>" src="<?php echo FRONT_SITE_IMG.'icon/block.png' ?>" alt="" style="padding: 7px;">
                                    </span>
                                </td>
                                <?php
                                
                                $oneDay = strtotime('1 day 30 second', 0);
                                for($i=1;$i<=10;$i++){
                                    $day = $current_date + ($i * $oneDay) - $oneDay; 
                                    $room = roomExist($room_id,date('Y-m-d',$day),date('Y-m-d',$current_date + ($i * $oneDay)));
                                    $active = 1;
                                    $countBookRoom = countTotalBooking($room_id,date('Y-m-d',$day),date('Y-m-d',$current_date + ($i * $oneDay)));
                                    $countQPBookRoom = countTotalQPBooking($room_id,date('Y-m-d',$day));

                                    $roomPrice = getRoomLowPriceByIdWithDate($room_id, date('Y-m-d',$day));

                                    if($roomPrice > settingValue()['advancePay']){
                                        $bookRoom = "<span class='bookRoom bg-gradient-primary '>".$countQPBookRoom."</span>";
                                    }else{
                                        $bookRoom = "<span class='bookRoom bg-gradient-info'>".$countBookRoom."</span>";
                                    }

                                    
                                    $dateCheck = date('Y-m-d',$day);
                                    $statusCheck = inventoryCheck($dateCheck, $room_id);
                                    
                                    

                                    if($statusCheck == 0){
                                        $statusClass = 'deactivate';
                                    }else{
                                        $statusClass = 'activate';
                                    }
                                    
                                    if($room == 0){
                                        $statusClass = 'deactivate';
                                    }


                                    
                                    if($countBookRoom == '' && $countQPBookRoom == ''){
                                        $bookRoom = '';
                                    }
                                    
                                    

                                    echo " 
                                        <td class='center $statusClass'>
                                            <span><input data-date='$dateCheck' data-rid='$room_id' class='inlineRoomNo' type='text' name='inlineRoomNo' value='$room'></span>
                                            $bookRoom
                                        </td>
                                    ";
                                }
                                
                                ?>
                            </tr>
                        <?php }
                    }
                ?>
            </table>
        </div>
    </div>


<?php } ?>