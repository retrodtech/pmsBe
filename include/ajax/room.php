<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'add_to_room.php');
$obj = new add_to_room();
$site = SERVER_INCLUDE_PATH;

// pr($_POST);


if(isset($_POST['type'])){
    $type = $_POST['type'];

    if($type == 'load_checkout_section'){
        if(isset($_SESSION['room']) && !empty($_SESSION['room'])){
            if(isset($_SESSION['room'])){  ?>
        
                <div class="booking-summary-box mobile-margin-top" style="overflow:hidden">
                            <div class="room-booking-summary"><br>
                
                <?php
                    $count = 0;
                    $totalPrice = 0;
                    $active = '';
                    foreach($_SESSION['room'] as $key=>$val){
                        $rdid = explode('-',$key)[0];
                        $count ++;
                        
                        if($count == 1){
                            $active = 'active';
                        }else{
                            $active = '';
                        }
                        
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
                        
                        
                        $singleRoomPriceCalculator = SingleRoomPriceCalculator($rid, $rdid, $adult, $child , $noRoom, $night, $roomPrice, $childPrice , $adultPrice, $couponCode);
                        
                        
                        if(isset($_SESSION['couponCode'])){
                            $code = $_SESSION['couponCode'];
                            $value = '' ;
                            $couponHTML = "
                                <li style='display: flex;'><div style='width: 100%;position: relative;justify-content: space-between;align-items: center;background: #f1f1f1;padding: 5px 10px;'><span>Coupon</span> <div style='position: relative;'> <small style='color: green;font-weight: 700;'>$code</small>  <span id='couponCloss' style='position: absolute;top: -35px;right: -20px;cursor: pointer;background: red;width: 20px;height: 20px;border-radius: 50%;display: flex;justify-content: center;align-items: center;color: white;font-weight: 700;'>X</span></div> </div></li> 
                            ";
                        }else{
                            $couponHTML = '
                                <li id="couponInputContent" style="display: flex;"><div class="content"><input id="couponValue" type="text" placeholder="Enter Coupon Code"> </div> <input type="submit" value="Add Coupon" id="add_coupon"> </li> <li style="display: block;padding: 0;height: auto; border-top: none !important"><span id="couponCodeError" style="padding: 5px 0;font-size: 12px;color: red;font-weight: 500;"></span></li>
                            ';
                        }
                        
                        $totalPrice += $singleRoomPriceCalculator[0]['total'];
                        
                        
                        ?>
                        
        
                                <div class="guestContent <?php echo $active ?>">
                                    <div class="closeGuestContent badge bg-gradient-danger shadow" data-key="<?php echo $key ?>">X</div>
                                    <?php
                                        if(isset($_SESSION['payByRoom']) && $_SESSION['payByRoom'] == 'Yes'){
                                            echo "<input class='payByRoom' name='payByRoom' type='checkbox' value='$key'>";
                                        }
                                    ?>
                                    <div class="box1">
                                        <div id="day-list">
                                            <div class="day-list" style="max-width: 300px;margin: 0 auto;">
                                                <div class="row">
                                                    <div class="col-md-8 m4">
                                                        <ul style="text-align: left;">
                                                            <li style="display: inline-block;"><b>Check In:</b> <span class="roomCheckinDate"><?php echo formatingDate($checkInTime) ?></span></li>
                                                            <li style="display: inline-block;"><b>Check Out:</b> <span class="roomCheckoutDate <?php echo $key ?>"><?php echo formatingDate($checkInOut) ?></span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4 m4">
            
                                                        <div class="quantity">
                                                            <a data-key="<?php echo $key ?>" data-rid="<?php echo $rid ?>" href="#" class="quantity__minus"><span>-</span></a>
                                                            <input name="quantity" type="text" class="quantity__input noOfight <?php echo $key ?>" value="<?php echo $night ?>" >
                                                            <a data-key="<?php echo $key ?>" data-rid="<?php echo $rid ?>" href="#" class="quantity__plus"><span>+</span></a>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
            
                                        <div class="container-">
                                            <div id="room_summary">
                                                <div class="room_summary_box">
                                                    <p><b><?php echo ucfirst(getRoomHeaderById($rid)) ?></b></p>
                                                    <ul>
                                                        <li><span>Room Price: </span> <span>Rs <?php 
                                                        
                                                            echo $singleRoomPriceCalculator[0]['room'];
                                                            
                                                        ?></span></li>
                                                        
                                                        <li>
                                                            <span>Adults: <?php echo $singleRoomPriceCalculator[0]['adultPrint']; ?></span>  <span>Rs <?php
                                                                echo $singleRoomPriceCalculator[0]['adult'] ;
                                                            ?></span>
                                                        </li>
                                                        <li>
                                                                <span>Child: <?php echo $singleRoomPriceCalculator[0]['childPrint'];  ?></span> <span>Rs <?php
                                                            
                
                                                            echo  $singleRoomPriceCalculator[0]['child'] ;
                                                            
                                                            ?></span> 
                                                        </li>  
                                                        
                                                        <?php 

                                                            if($singleRoomPriceCalculator[0]['couponCode'] != ''){
                                                                $couponPer = $singleRoomPriceCalculator[0]['couponCode'];
                                                                $couponPrice = $singleRoomPriceCalculator[0]['couponPrice'];
                                                                echo '
                                                                    <li><span>Coupon ( '.$couponPer.'  ):</span> <span id="gstPrint">- Rs '.$couponPrice.'</span></li>
                                                                ';
                                                            }
            
                                                            $nightPrice = $singleRoomPriceCalculator[0]['nightPrice'];
                                        
                                                            echo "
                                                            <li>
                                                                <span>Night</span>
                                                                <span>Rs <strong class='roomNightUpdate $key'>$nightPrice</strong> </span>
                                                            </li>
                                                        ";
                                                            
                                                            
                                                        ?>
                                                        
                                                        <li>
                                                            <span>GST ( <?php echo $singleRoomPriceCalculator[0]['gstPer'] ?>% ):</span> <span id="gstPrint">Rs <strong class="updateRoomGst <?php echo $key ?>"><?php echo $singleRoomPriceCalculator[0]['gst'] ?></strong></span>
                                                        </li>

                                                        <li>
                                                            <span>Total:</span> <span >Rs <strong class="updateRoomTotalPrice <?php echo $key ?>"><?php echo $singleRoomPriceCalculator[0]['total'] ?></strong></span>
                                                        </li>
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box2">
                                        <div class="content">
                                            <div class="drow">
                                                <h4><?php echo ucfirst(getRoomHeaderById($rid)) ?></h4>
                                                <p class="shortDateUpdate <?php echo $key ?>"><?php echo getDateFormatByTwoDate($checkInTime,$checkInOut) ?></p>
                                            </div>
                                            <div class="drow">
                                                <ul>
                                                    <li>
                                                        <img src="<?php echo FRONT_SITE_IMG ?>icon/adult.png" alt="Adult Icon">
                                                        <p><?php echo $adult ?></p>
                                                    </li>
                                                    <li>
                                                        <img src="<?php echo FRONT_SITE_IMG ?>icon/child.png" alt="Child Icon">
                                                        <p><?php echo $child ?></p>
                                                    </li>
                                                </ul>
                                                <strong>Rs <span class="totalRoomPriceupdate <?php echo $key ?>"><?php 
                                                        
                                                        echo $singleRoomPriceCalculator[0]['total'];
                                                        
                                                    ?></span>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                
                        <?php  } ?>
        
                        <ul>
                            <li id="pickupContent" style="display:none"></li>
                                <?php
                                
                                    echo $couponHTML;

                                    $_SESSION['gossCharge'] = $totalPrice;
                                
                                ?>
                                
                        </ul>
        
                        <div class="room-services"><br></div>
                                <?php $_SESSION['roomTotalPrice'] = $totalPrice ?>
                                <div class="room_summary_box row" style="margin: 0;">
                                    <div class="col-6">Total Amount</div>
                                    <div class="col-6" style="display: flex;justify-content: end;"> <b>Rs <span id="totalPriceValue"><?php echo number_format(totalSessionPrice()['price'],2)  ?></span></b></div>
                                </div>
                                <br/>
                                <div style="display: inline-block;margin-bottom: 15px;">
                                        <?php
                                            $pickUp = settingValue()['pckupDropCaption'];
                                            if(settingValue()['pckupDropStatus'] == 1){
                                                $select = '';
                                                calculateTotalBookingPrice();
                                                if(isset($_SESSION['pickUp']) && $_SESSION['pickUp'] != ''){
                                                    $select = 'checked';
                                                }
                                                echo '<div class="pickup">
                                                        <input '.$select.' type="checkbox" name="pickup" id="pickup">
                                                        <label for="pickup">Pick & Drop <span class="tool" data-tooltip="'.$pickUp.'">
                                                                                                <i class="far fa-question-circle"></i>
                                                                                            </span></label>
                                                    </div>';
                                            }
                                        ?>
            
                                        <?php
                                            $partial = settingValue()['partialPaymentCaption'];
                                            if(settingValue()['partialPaymentStatus'] == 1){
                                                calculateTotalBookingPrice();
                                                $select = '';
                                                if(isset($_SESSION['partial']) && $_SESSION['partial'] == 'Yes'){
                                                    $select = 'checked';
                                                }
                                                echo '<div class="partial">
                                                        <input '.$select.' type="checkbox" name="partial" id="partial">
                                                        <label for="partial">Pay '.$percentage.'% Now<span class="tool" data-tooltip="'.$partial.'">
                                                                                                <i class="far fa-question-circle"></i>
                                                                                            </span></label>
                                                    </div>';
                                            }
                                        ?>

                                        <?php
                                            $payByRoom = settingValue()['payByRoom'];
                                            if($payByRoom == 1){
                                                $select = '';
                                                if(isset($_SESSION['payByRoom']) && $_SESSION['payByRoom'] == 'Yes'){
                                                    $select = 'checked';
                                                }
                                                echo '  <div class="payByRoom">
                                                            <input type="checkbox" name="payByRoom" '.$select.' id="payByRoom">
                                                            <label for="payByRoom">Pay By Room<span class="tool" data-tooltip="'.$payByRoom.'"> <i class="far fa-question-circle"></i></span></label>
                                                        </div>';
                                            }
                                        ?>
                                        
                                        
                                    </div>
                                <div class="room_summary_box" style="display: flex;justify-content: space-between;align-items: self-start;">
                                    
                                    
                                    <?php
                                        $BookingSite = FRONT_BOOKING_SITE;
                                        echo "<div> <a href='$BookingSite' class='btn btn-outline-info'><span class=''> Add Room</span></a></div>";
                                        
                                        echo "<div class='btn btn-primary' id='continue_btn'> <span class=''> Continue</span></div>";
                                        
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                <?php
        
        
                }else{
                    echo "<img src='admin/img/icon/spinner.gif' style='width: 128px;position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);'>";
                }
        }
    }
    
    if($type == 'add_room'){
        
        $slug = $_POST['slug'];
        
        $header = $_POST['header'];
        $bedType = $_POST['bedType'];
        $roomCapacity = $_POST['roomCapacity'];
        $amenities = $_POST['amenities'];
        $titlearr = $_POST['title'];
        $singleRoomPriceArr = $_POST['singleRoomPrice'];
        $doubleRoomPriceArr = $_POST['doubleRoomPrice'];
        $image = $_FILES['roomImage']['name'];
        $noAdult = $_POST['noAdult'];
        $noChild = $_POST['noChild'];
        $extraAdultArr = $_POST['extraAdult'];
        $extraChildArr = $_POST['extraChild'];
        $room = $_POST['totalRoom'];
        $mrp = $_POST['mrp'];
        $roomdesc = '';
        $added_on=date('Y-m-d h:i:s');

        mysqli_query($conDB, "insert into room(slug,header,bedtype,roomcapacity,noAdult,noChild,description,add_on,status,totalroom,mrp) values('$slug','$header','$bedType','$roomCapacity','$noAdult','$noChild','$roomdesc','$added_on','1','$room','$mrp')");
        $rid = mysqli_insert_id($conDB);
    
        foreach($amenities as $key=>$val){
            mysqli_query($conDB, "insert into room_amenities(room_id,amenitie_id) values('$rid','$val')");
        }
    
        $extension=array('jpeg','jpg','JPG','png','gif');
        foreach($image as $key=>$val){
            $roomImgName = $_FILES['roomImage']['name'][$key];
            $roomImgTemp = $_FILES['roomImage']['tmp_name'][$key];
            $ext=pathinfo($roomImgName,PATHINFO_EXTENSION);
            
            if(in_array($ext,$extension)){
                $newfilename=rand(100000,999999).".".$ext;
                move_uploaded_file($roomImgTemp, SERVER_ROOM_IMG.$newfilename);
                mysqli_query($conDB, "insert into room_img(room_id,image) values('$rid','$newfilename')");
            }
        }
       
    
        foreach($titlearr as $key=>$val){
            $title = $titlearr[$key];
            $singleRoomPrice = $singleRoomPriceArr[$key];
            $doubleRoomPrice = $doubleRoomPriceArr[$key];
            $extraAdult = $extraAdultArr[$key];
            $extraChild = $extraChildArr[$key];
            
            mysqli_query($conDB, "insert into room_detail(room_id,title,singlePrice,doublePrice,extra_adult,extra_child,status) values('$rid','$title','$singleRoomPrice','$doubleRoomPrice','$extraAdult','$extraChild','1')");
        }

    
    }

    if($type == 'update_room'){
        
        $update_id = $_POST['update_id'];
        $header = $_POST['header'];
        $bedType = $_POST['bedType'];
        $roomCapacity = $_POST['roomCapacity'];  
        $roomdesc = $_POST['roomdesc'];

        $noAdult = $_POST['noAdult'];    
        $noChild = $_POST['noChild'];           
        
        $room = $_POST['totalRoom'];
        $added_on=date('Y-m-d h:i:s');
        $mrp = $_POST['mrp'];
       
        mysqli_query($conDB, "update room set header = '$header', bedtype = '$bedType', totalroom='$room', roomcapacity='$roomCapacity' , noAdult='$noAdult', noChild='$noChild',mrp='$mrp',description='$roomdesc' where id='$update_id'");
        

     
        if(isset($_POST['title'])){
            $titlearr = $_POST['title'];
            $roomPrice = $_POST['roomPrice'];
            $extraAdultArr = $_POST['extraAdult'];
            $extraChildArr = $_POST['extraChild'];
            foreach($titlearr as $key=>$val){
                $title = $titlearr[$key];
                $price = $roomPrice[$key];
                $extraAdult = $extraAdultArr[$key];
                $extraChild = $extraChildArr[$key];
                
                mysqli_query($conDB, "insert into room_detail(room_id,title,price,extra_adult,extra_child,status) values('$update_id','$title','$price','$extraAdult','$extraChild','1')");
               
            }
        }
        $amenities = $_POST['amenities'];
        $amenitiesLoop = getAmenitieIdByRoomId($update_id);
        foreach($amenitiesLoop as $num){
            if (!in_array($num,$amenities)) {
                mysqli_query($conDB, "delete from room_amenities where room_id = '$update_id' and amenitie_id = '$num'");
            } 
        }
        
        foreach($amenities as $key=>$val){
            if(checkAmenitiesById($update_id,$val) == 0){
                mysqli_query($conDB, "insert into room_amenities(room_id,amenitie_id) values('$update_id','$val')");
            }
        }

        $image = $_FILES['roomImage']['name'];
        $extension=array('jpeg','jpg','JPG','png','gif');
        foreach($image as $key=>$val){
            $roomImgName = $_FILES['roomImage']['name'][$key];
            $roomImgTemp = $_FILES['roomImage']['tmp_name'][$key];
            $ext=pathinfo($roomImgName,PATHINFO_EXTENSION);
            if(in_array($ext,$extension)){
                $newfilename=rand(100000,999999).".".$ext;
                move_uploaded_file($roomImgTemp, SERVER_ROOM_IMG.$newfilename);
                mysqli_query($conDB, "insert into room_img(room_id,image) values('$update_id','$newfilename')");
            }
        }
        
        

        $titleUploadArr = $_POST['titleUpload'];
        $singleRoomPriceUploadArr = $_POST['singleRoomPriceUpload'];
        $doubleRoomPriceUploadArr = $_POST['doubleRoomPriceUpload'];
        $room_detail_id = $_POST['room_detail_id'];
        $extraAdultUploadArr = $_POST['extraAdultUpload'];
        $extraChildUploadArr = $_POST['extraChildUpload'];
        
        foreach($titleUploadArr as $key=>$val){
            $title = $titleUploadArr[$key];
            $singleRoomPriceUpload = $singleRoomPriceUploadArr[$key];
            $doubleRoomPriceUpload = $doubleRoomPriceUploadArr[$key];
            $detail_id = $room_detail_id[$key];
            $extraAdult = $extraAdultUploadArr[$key];
            $extraChild = $extraChildUploadArr[$key];
            
            mysqli_query($conDB, "update room_detail set title='$title',singlePrice='$singleRoomPriceUpload',doublePrice='$doubleRoomPriceUpload',extra_adult='$extraAdult',extra_child='$extraChild' where id='$detail_id'");
            
        }
        $_SESSION['SuccessMsg'] = "Successfull Update record";
    
    }
    
    if($type == 'add_guest_section'){
        //    pr($_POST);
        $id = $_POST['id']; 
        $room_id = $_POST['room_id']; 
        $room = $_POST['room']; 

        // $userRoomChoose = $_SESSION['userRoomChoose'];
        
        $adultNo = roomMaxCapacityById($room_id);
        $adultList = '';
        for($i=1; $i <= $adultNo; $i++){
            if($i == getMinRoomAdultCountByIdRdid($room_id, $id)){
                $adultList .= "<option selected value='$i'>$i</option>";
            }else{
                $adultList .="<option value='$i'>$i</option>";
            }
        }

        $childAve = 2;
        $childList = '';
        for($i=0; $i <= $childAve; $i++){
            
            if($i == getRoomChildCountById($room_id)){
                $childList .= "<option selected value='$i'>$i</option>";
            }else{
                $childList .= "<option value='$i'>$i</option>";
            }
        }
        $html = '';
        for ($i=1; $i <= $room ; $i++) { 

            if($room == $i){
                $actionRoom = '
                    <input id="roomInput" name="room[]" type="text" value="'.$i.'">
                    <div class="roomAction">
                        <span class="roomIncrement" data-id="'.$room_id.'" data-rdid="'.$id.'">+</span>
                        <span class="roomDecrement" data-id="'.$room_id.'" data-rdid="'.$id.'">-</span>
                    </div>
                ';
            }else{
                $actionRoom = '
                    <input name="room[]" type="text" value="'.$i.'">
                ';
            }

            $html .= '
                    <div class="row mb-2">
                        <div class="col-4">
                            <div class="form-group inlineFlex" style="height: 40px;">
                                <span class="icon roomIcon mr-2">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 65.8 105.3" style="enable-background:new 0 0 65.8 105.3; width: 30px;height: 30px;fill: #3d5a80;" xml:space="preserve">
                                    <g>
                                        <path d="M3.7,2.4c18,0,35.7-0.1,53.3,0c4.6,0,6.3,2.4,6.4,8.2c0,18.8,0,37.6,0,56.5c0,3.7,0,7.3,0,11c-0.1,4.5-2.4,6.7-6.9,6.8
                                            c-5,0-10,0-15.8,0c0,4.7,0,9.2,0,13.6c-0.1,5.9-2.1,7.1-6.9,4.2c-9.1-5.6-18.2-11.1-27.1-16.9c-1.7-1.1-3.5-3.5-3.5-5.4
                                            C3,55.6,3.1,30.8,3.1,6C3.1,5,3.4,4,3.7,2.4z M8.7,9.6c0,2.9,0,4.6,0,6.4c0,18.1,0.5,36.3-0.2,54.4c-0.3,8.2,1.9,13.2,9.4,16.7
                                            c6,2.8,11.5,6.9,17.9,10.8c0-23.7,0-46.3-0.1-68.9c0-1.2-0.5-2.9-1.4-3.4C26.1,20.4,17.8,15.3,8.7,9.6z M57.9,79.8
                                            c0-24.3,0-48.2,0-71.9c-14.1,0-27.7,0-42.5,0c7.8,4.8,14.5,9.2,21.4,13.2c3,1.7,4.1,3.8,4,7.2c-0.1,15.3-0.1,30.6-0.1,46
                                            c0,1.8,0,3.5,0,5.5C46.8,79.8,52.1,79.8,57.9,79.8z"/>
                                        <path d="M33.2,62.2c-1.6,0-2.9,0-4.5,0c0-3.2,0-6.2,0-9.5c1.5,0,2.9,0,4.5,0C33.2,55.8,33.2,58.8,33.2,62.2z"/>
                                    </g>
                                    </svg>
                                </span>
                                <span class="inlineFlex roomSelect">
                                    '.$actionRoom.'
                                </span>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group inlineFlex" style="height: 40px;width: 100%;">
                                        <span class="icon">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 95.6 193.3" style="enable-background:new 0 0 95.6 193.3;width: 30px;height: 28px;fill: #3d5a80;" xml:space="preserve">
                                                <g>
                                                    <path d="M25.2,82.4c-2.6,6.9-5.1,13.9-7.7,20.8c-1.6,4.3-4.5,6.6-9.2,6c-4.9-0.6-8.1-5.4-6.3-10.4C6.8,85.4,11.4,72,16.9,59
                                                        c3.5-8.4,11-12.3,20-12.6c7.3-0.2,14.7-0.2,22,0c10.3,0.3,17.8,5.2,21.6,14.9c4.5,11.8,8.8,23.7,13.1,35.6
                                                        c2.1,5.8,0.3,10.5-4.3,12.1c-4.7,1.6-8.8-0.9-11-6.7c-2.5-6.6-4.9-13.3-8.3-19.7c0,1.8,0,3.5,0,5.3c0,30.3,0,60.7,0,91
                                                        c0,1.5,0.1,3-0.1,4.5c-0.6,5.4-4.5,9-9.6,8.9c-5.1-0.1-9-3.8-9.2-9.3c-0.2-5.5-0.1-11-0.1-16.5c0-14,0.1-28-0.1-42
                                                        c0-1.7-1.6-3.4-2.5-5.2c-1,1.7-3,3.5-3,5.2c-0.2,19.3-0.1,38.7-0.2,58c0,5.6-3.7,9.4-8.7,9.7c-5.2,0.3-9.5-3.3-10.1-8.9
                                                        c-0.2-1.6-0.1-3.3-0.1-5c0-30.2,0-60.3,0-90.5c0-1.8,0-3.5,0-5.3C25.9,82.5,25.5,82.4,25.2,82.4z"/>
                                                    <path d="M47.9,2.3C58.7,2.2,67.5,11,67.7,21.9c0.1,10.8-8.7,19.9-19.5,20c-10.7,0.1-19.6-8.8-19.8-19.7
                                                        C28.3,11.4,37.1,2.3,47.9,2.3z"/>
                                                </g>
                                            </svg>
                                        </span>
                                        
                                        <select id="adult-label" class="form-control" name="adult[]">
                                                '.$adultList.'
                                            </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group inlineFlex" style="height: 40px;width: 100%;">
                                        <span class="icon">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 119.5 165.8" style="enable-background:new 0 0 119.5 165.8; width: 30px;height: 28px;fill: #3d5a80;" xml:space="preserve">
                                                <g>
                                                    <path d="M85.3,90.2c0,1.6,0,3.2,0,4.8c0,18.5,0,37,0,55.5c0,7.8-4.4,12.7-10.9,12.8c-6.6,0-10.9-4.9-11-12.7c-0.1-6.7,0-13.3,0-20
                                                        c0-2.5,0.1-5.7-3.4-5.2c-1.3,0.2-3,3.2-3.1,5c-0.4,7.3,0,14.7-0.3,22c-0.2,5.8-4.1,10-9.3,10.8c-4.7,0.8-9.7-1.7-11.4-6.2
                                                        c-0.9-2.2-1.1-4.9-1.1-7.3c-0.1-18.2,0-36.3,0-54.5c0-1.8,0-3.5,0-5.3c-0.5-0.3-1-0.6-1.5-0.9c-3.8,4.1-7.5,8.3-11.4,12.2
                                                        c-5.6,5.7-12,6.1-16.7,1.3c-4.7-4.7-4.2-11.3,1.2-16.8c7.1-7.1,14.2-14.1,21.3-21.1c4.8-4.9,10.7-7.2,17.5-7.2c10,0,20,0,30,0
                                                        c6.8,0,12.7,2.3,17.5,7.2c7.1,7.2,14.3,14.4,21.4,21.7c5.1,5.2,5.4,11.9,0.9,16.4c-4.6,4.6-11.1,4.2-16.4-1c-4-3.9-7.9-8-11.9-12.1
                                                        C86.2,89.9,85.8,90.1,85.3,90.2z"/>
                                                    <path d="M34.7,28.4C34.9,14.3,46.2,3.2,60.3,3.3c14,0.1,25.1,11.6,25,25.7c-0.1,14-11.7,25.4-25.6,25.1
                                                        C45.7,53.9,34.6,42.5,34.7,28.4z"/>
                                                </g>
                                            </svg>
                                        </span>
                                        
                                        <select name="kids[]" id="child-label" class="form-control">
                                            '.$childList.'
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            ';

        }
       
        ?>
            <form id="room_guest_select_form" method="POST">

                <?php echo $html ?>

                <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                <input type="hidden" name="room_detail_id" value="<?php echo $id ?>">
                <input type="hidden" value="confirm_room" name="type">
                <div class="row btn_row">
                    <div class="col-6"><a class="btn btn-light" id="remove_guest_section">CANCEL</a></div>
                    <div class="col-6" style="display: flex;justify-content: end;"><input type="submit" class="btn btn-dark" value="Confirm"></div>
                </div>
            </form>
    
        <?php
    }
    
    if($type== 'confirm_room'){
        // pr($_POST);
        $roomArr = $_POST['room'];
        $adultArr = $_POST['adult'];
        $kidsArr = $_POST['kids'];
        $rid = $_POST['room_id'];
        $rdid = $_POST['room_detail_id'];
        $night = 1;
        $adultNo = roomMaxCapacityById($rid);
        
        $checkIn = $_SESSION['checkIn'];
        $checkout = $_SESSION['checkout'];

        // if(isset($_SESSION['room']) && !empty($_SESSION['room'])){
        //     $fistKey = array_keys($_SESSION['room'])[0];
        //     $checkIn = $_SESSION['room'][$fistKey]['checkIn'];
        //     $checkout = $_SESSION['room'][$fistKey]['checkout'];
        // }

        // if(roomExist($rid) >= $room){
        //     $room = $room;
        // }else{
        //     $room = 1;
        // }

        // if($adultNo <= $adult){
        //     $adult = $adultNo;
        // }

        foreach($roomArr as $key=>$val){
            $room = $val;
            $adult = $adultArr[$key];
            $kids = $kidsArr[$key];
            $night = getNightCountByDay($checkIn,$checkout);
            $obj->addroom($rid,$room,$adult,$kids,$night,$rdid,$checkIn,$checkout,$key);
        }
        
        
        
        
    
    
    }
    
    if($type == 'persionCheckout'){
   
        $personName = $_POST['personName'];
        $personEmail = $_POST['personEmail'];
        $personPhoneNo = $_POST['personPhoneNo'];
        $companyName = '';
        $companyGst = '';

        $hotelId = 1;
        $add_on=date('Y-m-d h:i:s');

        $_SESSION['personName']=$personName;
        $_SESSION['personEmail']=$personEmail;
        $_SESSION['personPhoneNo']=$personPhoneNo;
        
        $night = $_SESSION['night_stay'];
    
        if(isset($_POST['companyName'])){
            $companyName = $_POST['companyName'];
            $companyGst = $_POST['companyGst'];
        }

        if(isset($_SESSION['partial'])){
            $partial = $_SESSION['partial'];
        }else{
            $partial = '';
        }
        if(isset($_SESSION['couponCode'])){
            $couponCode = $_SESSION['couponCode'];
        }else{
            $couponCode = '';
        }
        if(isset($_SESSION['pickUp'])){
            $pickUp = $_SESSION['pickUp'];
        }else{
            $pickUp = 0;
        }

        $bid = getBookingNumber();
        $grossAmount = $_SESSION['gossCharge'];
        $userPay = $_SESSION['roomTotalPrice'];

        
        $noOfRum = 1;
        $bookingSrc = 5;

        $sql = "insert into booking(hotelId,bookinId,payment_status,add_on,couponCode,pickUp,userPay,nroom,bookingSource) values('$hotelId','$bid','pending','$add_on','$couponCode','$pickUp','$userPay','$noOfRum','$bookingSrc')";

        mysqli_query($conDB, $sql);
        $_SESSION['OID']=mysqli_insert_id($conDB);
        $bookingId = $_SESSION['OID'];

        $roomNumStr = '';
        $roomNumArr = array();

        if(!empty($personName)){
            if(isset($_SESSION['room'])){
                
                $countKey = 0;
                foreach($_SESSION['room'] as $key=>$val){
                    $countKey ++;
                    $rdid = explode('-',$key)[0];

                    $rid = $_SESSION['room'][$key]['roomId'];
                    $child = $_SESSION['room'][$key]['child'];
                    $adult = $_SESSION['room'][$key]['adult'];
                    $checkInTime = $_SESSION['room'][$key]['checkIn'];
                    $checkInOut = $_SESSION['room'][$key]['checkout'];
                    $noAdult = $_SESSION['room'][$key]['adult'];
                    $noRoom = $_SESSION['room'][$key]['room'];
                    $night = $_SESSION['room'][$key]['night'];

                    $roomPrice = getRoomPriceById($rid,$rdid, $adult, $checkInTime);
                    $adultPrice = getAdultPriceByNoAdult($adult,$rid,$rdid, $checkInTime);
                    $childPrice = getChildPriceByNoChild($child,$rid,$rdid, $checkInTime);

                    $singleRoomPriceCalculator = SingleRoomPriceCalculator($rid, $rdid, $adult, $child , $noRoom, $night, $roomPrice, $childPrice , $adultPrice, $couponCode);

                    $gstPer = $singleRoomPriceCalculator[0]['gstPer'];
                    $total = $singleRoomPriceCalculator[0]['total'];

                    $roomNum = getRoomNumber('',1,$rid,$checkInTime,$checkInOut)[0]['roomNo'];
                    
                    array_push($roomNumArr, $roomNum);
                    $bid = $_SESSION['OID'];

                    $sql = "insert into bookingdetail(bid,roomId,roomDId,room_number,adult,child) values('$bid','$rid','$rdid','$roomNum','$adult','$child')";

                    mysqli_query($conDB, $sql);
                    
                }

                $roomNumStr = implode(',',$roomNumArr);
                
                

                mysqli_query($conDB, "insert into guest(hotelId,bookId,roomnum,owner,name,email,phone,company_name,comGst) values('$hotelId','$bookingId','$roomNumStr','1','$personName','$personEmail','$personPhoneNo','$companyName','$companyGst')");
                   
        
                unset($_SESSION['room']);
                echo 'successfull book';

            }
        }
    
    }
    
    // if($type == 'showBookingForm'){

        

    //     $amount =  $_SESSION['roomTotalPrice'];
        
    //     $api = new Api($keyId, $keySecret);
    //     $orderData = [
    //         'receipt'         => 3456,
    //         'amount'          => $amount * 100,
    //         'currency'        => 'INR',
    //         'payment_capture' => 1
    //     ];
        
    //     $name = $_SESSION['personName'];
    //     $email = $_SESSION['personEmail'];
    //     $phone = $_SESSION['personPhoneNo'];
    //     $logo = FRONT_SITE_IMG.hotelDetail()['logo'];

    //     $razorpayOrder = $api->order->create($orderData);
    //     $razorpayOrderId = $razorpayOrder['id'];
    //     $_SESSION['razorpayOrderId'] = $razorpayOrderId;
    //     $displayAmount = $amount = $orderData['amount'];

    //     if ($displayCurrency !== 'INR') {
    //         $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    //         $exchange = json_decode(file_get_contents($url), true);

    //         $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
    //     }
        
        
        
    //     $data = [
    //         "key"               => $keyId,
    //         "amount"            => $amount,
    //         "name"              => $_SESSION['title'],
    //         "description"       => $_SESSION['desc'],
    //         "image"             => $logo,
            
    //         "prefill"           => [
    //         "name"              => $name,
    //         "email"             => $email,
    //         "contact"           => $phone,
    //         ],
            
    //         "notes"           => [
    //         "address"              => SITE_NAME,
    //         ],
    //         "order_id"          => $razorpayOrderId,
    //     ];

    //     if ($displayCurrency !== 'INR')
    //     {
    //         $data['display_currency']  = $displayCurrency;
    //         $data['display_amount']    = $displayAmount;
    //     }

    //     unset($_SESSION['roomTotalPrice']);
    //     unset($_SESSION['personName']);
    //     unset($_SESSION['personEmail']);
    //     unset($_SESSION['personPhoneNo']);
        
    //     echo json_encode($data);
    //     die();
    // }

    if($type == 'couponCode'){
        $couponValu = trim($_POST['couponValu']);
        $sql = mysqli_query($conDB, "select * from couponcode where coupon_code = '$couponValu'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_assoc($sql);
            $coupon_type = $row['coupon_type'];
            $min_value = $row['min_value'];
            $coupon_value = $row['coupon_value'];
            $expire_on = $row['expire_on'];
            $status = $row['status'];
            

            
            if($coupon_value === $couponValu){
                $data["type"] = "error";
                $data['msg'] = "Coupon Code Is Invalid";
            }elseif($status == 0){
                $data["type"] = "error";
                $data['msg'] = "Coupon Code Is Deactive";
            }elseif(strtotime($expire_on) < strtotime(date('Y-m-d'))){
                $data["type"] = "error";
                $data['msg'] = 'Coupon code is expired';
            }else{
                $_SESSION['couponCode'] = $couponValu;
                if($coupon_type == 'P'){
                    $data["type"] = "success";
                }
                if($coupon_type == 'F'){
                    $data["type"] = "success";
                }
            }


        }else{
            $data["type"] = "error";
            $data['msg'] = 'Invalid Coupon Code.';
        }
        echo json_encode($data);
    }
    
    if($type == 'removeCouponCode'){
        unset($_SESSION['couponCode']);
    } 

    if($type == 'LoadPrice'){
        echo number_format(totalSessionPrice()['price'],2);
    }

    if($type == 'pickup'){
        echo $pickupPrice = settingValue()['pckupDropPrice'];
        $_SESSION['pickUp'] = $pickupPrice;
        calculateTotalBookingPrice();
    }

    if($type == 'removePickup'){
        echo $pickupPrice = settingValue()['pckupDropPrice'];
        unset($_SESSION['pickUp']);
        calculateTotalBookingPrice();
    }

    if($type == 'partial'){
        if(isset($_SESSION['payByRoom']) && $_SESSION['payByRoom'] == 'Yes'){

        }else{
            $_SESSION['partial'] = 'Yes';
            calculateTotalBookingPrice();
        }        
    }

    if($type == 'removePartial'){
        if(isset($_SESSION['payByRoom']) && $_SESSION['payByRoom'] == 'Yes'){

        }else{
            unset($_SESSION['partial']);
            calculateTotalBookingPrice();
        } 
        
    }
    
    if($type == 'updatePayRoom'){
        $id = $_POST['id'];
        $data = '';
        if($id == 0){
             $data .= "<option value='0'>Select Room</option>";
        }else{
            $ratePlanArr = getRatePlanArrById($id);
            
            foreach($ratePlanArr as $key=>$rPlanList){
                $id = $rPlanList['id'];
                $name = $rPlanList['rplan'];
                $price = $rPlanList['price'];
                if($key == 0){
                    $data .= "<option selected value='$id'><span>$name</span><span> ( ₹ $price )</span></option>";
                }else{
                    $data .= "<option value='$id'><span>$name</span><span> ( ₹ $price )</span></option>";
                }
            }
        }
        
        
        echo $data;
    }
    
    if($type == 'updatePayRatePlan'){
        $id = $_POST['id'];
        $ratePlanDetail = getRatePlanDetailById($id);
        $price = $ratePlanDetail[0]['price'];
        $gst = getGSTPrice($price);
        
        $persentageArr = [50,75,100];
        
        $data = '<div id="percentageCheck" class="row"> ';
        foreach($persentageArr as $key=>$percentageList){
            $PercentagePrice = ($price + $gst) * $percentageList / 100;
            if($key == 0){
                $req = 'required';
            }else{
                $req = '';
            }
            $data .= "<div class='col-md-4 col-sm-6'><input type='radio' name='percentageCheck' value='$percentageList' id='$percentageList' $req> <label for='$percentageList'><span class='percentage'>$percentageList %</span> <span>₹ $PercentagePrice</span></label> </div>";
        }
        
        $data .='</div>';
        
        $data .= '
        
        <div class="card mb-4">
            <h5 class="card-header d-flex align-items-center justify-content-between"><span>Payble <small
                        class="text-secondary">Amount</small></span></h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 d-flex mb-3">
                       
                    </div>
                    <div class="col-md-8">
                        <dl class="row">
                            <dt class="col-5">Room:</dt>
                            <dd class="col-7">'.$price.'</dd>
                            <dt class="col-5">GST ( '.getGSTPercentage($price).'% ):</dt>
                            <dd class="col-7">'.getGSTPrice($price).'</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        
        ';
        
        echo $data;
        
    }

    if($type == 'checkRoomNumber'){
        $id = $_POST['id'];
        $room = $_POST['room'];
        $action = $_POST['action'];
        

        if(roomExist($id) >= 15){
            $roomDisplay = 15;
        }else{
            $roomDisplay = roomExist($id);
        }

        if($roomDisplay > $room){
            if($action == 'inc'){
                $room ++;
            }
        }

        if($action == 'dec'){
            $room --;
        }

        if($room < 1){
            $room = 1;
        }else{
            $room ;
        }

        $_SESSION['userRoomChoose'] = $room;
        echo $room;

    }

    if($type == 'getParentIdData'){
        $id = $_POST['id'];
        $sql = mysqli_query($conDB, "select * from room where id = '$id' and pId = '0'");
        $data = array();
        if(mysqli_num_rows($sql) > 0){
            $update_row = mysqli_fetch_assoc($sql);
            $uid = $update_row['id'];
            $header = $update_row['header'];
            $bedtype = $update_row['bedtype'];
            $totalroom = $update_row['totalroom'];
            $roomcapacity = $update_row['roomcapacity'];

            $noAdult = $update_row['noAdult'];
            $noChild = $update_row['noChild'];
            $slug = $update_row['slug'];
            
            $getRatePlanArrById = getRatePlanArrById($uid);

            $data[] = [
                'rid'=>$uid,
                'header'=>$header,
                'bedtype'=>$bedtype,
                'totalroom'=>$totalroom,
                'roomcapacity'=>$roomcapacity,
                'noAdult'=>$noAdult,
                'noChild'=>$noChild,
                'slug'=>$slug
            ];
        }

        echo json_encode($data[0]);
    }

    if($type == 'removeGustContent'){
        $key = $_POST['key'];
        $obj->removeroom($key);
    }
    

    if($type == 'payByRoom'){
        $_SESSION['payByRoom'] = 'Yes';
    }

    if($type == 'removePayByRoom'){
        unset($_SESSION['payByRoom']);
    }

    if($type == 'payByRoomCalculate'){
        $key = $_POST['key'];
        $total = 0;
        foreach($key as $keyList){
            $rdid = explode('-',$keyList)[0];
            $rid = $_SESSION['room'][$keyList]['roomId'];
            $child = $_SESSION['room'][$keyList]['child'];
            $adult = $_SESSION['room'][$keyList]['adult'];
            $checkInTime = $_SESSION['room'][$keyList]['checkIn'];
            $checkInOut = $_SESSION['room'][$keyList]['checkout'];
            $noAdult = $_SESSION['room'][$keyList]['adult'];
            $noRoom = $_SESSION['room'][$keyList]['room'];
            $night = $_SESSION['room'][$keyList]['night'];

            $roomPrice = getRoomPriceById($rid,$rdid, $adult, $checkInTime);
            $adultPrice = getAdultPriceByNoAdult($adult,$rid,$rdid, $checkInTime);
            $childPrice = getChildPriceByNoChild($child,$rid,$rdid, $checkInTime);
            

            if(isset($_SESSION['couponCode'])){
                $couponCode = $_SESSION['couponCode'];
            }else{
                $couponCode = '';
            }
            
            
            $singleRoomPriceCalculator = SingleRoomPriceCalculator($rid, $rdid, $adult, $child , $noRoom, $night, $roomPrice, $childPrice , $adultPrice, $couponCode);
            
            $total += $singleRoomPriceCalculator[0]['total'];
        }

        $_SESSION['roomTotalPrice'] = $total;
        echo $total;
    }
   
}



if(isset($_POST['night'])){
    $night = $_POST['night'];
    $key = $_POST['key'];
    $rid = $_POST['rid'];

    $check_in = strtotime($_SESSION['room'][$key]['checkIn']);
    $oldNight = $_SESSION['room'][$key]['night'];
    $_SESSION['room'][$key]['night'] = $night;



    $check_out = strtotime($_SESSION['room'][$key]['checkout']);
    $night_string = strtotime('1 day 00 second', 0);

    $check_out_time = ($check_in) + ($night * $night_string);

    $preCheckOutTime = $check_out_time - $night_string;

    if(loopRoomExist($rid,date('Y-m-d',$preCheckOutTime)) == 0){
        $_SESSION['room'][$key]['checkout'] = date('Y-m-d',$check_out);
        $_SESSION['room'][$key]['night'] = $oldNight;
        echo 'noNight';
    }else{
        $_SESSION['room'][$key]['checkout'] = date('Y-m-d',$check_out_time);
    }


    
    
}

?>