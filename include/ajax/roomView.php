<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$type = '';

if(isset($_POST['type'])){
    $type = $_POST['type'];
}

if($type == 'loadRoomView'){
  
    $si = 0;
    $pagination = '';
    
    $sql = "select * from roomnumber where id != ''";
        
    
    $limit_per_page = 15;
    
    $page = '';
    if(isset($_POST['page_no'])){
        $page = $_POST['page_no'];
    }else{
        $page = 1;
    }
    
    
    $offset = ($page -1) * $limit_per_page;
    
    $sql .= " ORDER BY roomNo ASC ";
    // $sql .= " ORDER BY roomNo ASC limit {$offset}, {$limit_per_page}";
    
    $html = '<div class="card"><div class="card-body"><div class="row">';

    $query = mysqli_query($conDB, $sql);
    $si = $si + ($limit_per_page *  $page) - $limit_per_page;
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $si ++;
            $rnid = $row['id'];
            $rn = $row['roomNo'];
            $roomId = $row['roomId'];
            $roomTypeSName = strtoupper(getRoomNameType($roomId)['sName']);
            $bid = '';
            $countAdult = 0;
            $maxAdult = 0;
            
            $positionPer = 0;
            $bookingImgUrl = '';

            $bookPersion = '___';
            $persionCheckin = 'Vacant';
            
            if(isset(getBookingData('',$rn,date('Y-m-d'))[0]['bid'])){    

                $maxAdult = getRoomAdultCountById($roomId);
                $countAdult = getBookingData('',$rn)[0]['adult'];                      
                $bid = getBookingData('',$rn)[0]['bid'];
                $positionPer = getPercentageByTwoValue($countAdult,$maxAdult);
                $bookingImgUrl = FRONT_SITE_IMG.'icon/source/'.getBookingSource(getBookingData('',$rn)[0]['bookingSource'])[0]['img'];

                if(isset(getGuestDetail($bid,1)[0]['name'])){
                    $bookPersion = ucfirst(getGuestDetail($bid,1)[0]['name']);
                    $persionCheckin = 'Check In';
                }
                
            }


            $html .= '<div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="content roomContent" data-roomnumber="'.$rn.'">
                                <div class="iconCon">
                                    <div>'.$rn.'</div>
                                    <div>'.$roomTypeSName.'</div>    
                                </div>
                                <div class="caption">
                                    <span>'.$bookPersion.'</span> <br/>
                                    <small class="opc5">'.$persionCheckin.'</small>
                                </div>
                                <ul class="stayPosition">
                                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-0">
                                        <div class="w-100">
                                            <div class="d-flex mb-2">
                                                <span class="me-2 text-sm font-weight-bold text-capitalize">Stay Positive</span>
                                                <span class="ms-auto text-sm font-weight-bold">'.$positionPer.'%</span>
                                            </div>
                                        <div>
                                            <div class="progress progress-md" style="height: 2px;">
                                                <div style="margin-top: 0; height: 2px; width:'.$positionPer.'%" class="progress-bar bg-gradient-info " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="roomViewfoot dFlex jcsb aic">
                                    <ul>
                                        <li> <button data-tooltip-top="Payment Due"><i class="fas fa-dollar-sign"></i></button></li>
                                        <li> <button data-tooltip-top="CP"><i class="fas fa-utensils"></i></button></li>
                                        <li> <button data-tooltip-top="Group Booking"><i class="fas fa-user-friends"></i></button></li>
                                        <li> <button data-tooltip-top="No Smoking"><i class="fas fa-smoking"></i></button></li>
                                    </ul>
                                    <img width="25" src="'.$bookingImgUrl.'" alt="">
                                </div>
                            </div>
                        </div>';            

        }
    }else{
       
    }

    $html .= '</div></div></div>';

    echo $html;
}

if($type == 'load_add_guest'){
    $bookingSource = '';
    $reservationType = '';
    foreach(getReservationType() as $key=>$reservationTypeList){
        $select = '';
        if($key == 0){
            $select = 'selected';
        }
        $id = $reservationTypeList['id'];
        $name = ucfirst($reservationTypeList['name']);
        $reservationType .=   "<option value='$id' $select>$name</option>";
    }

    foreach(getBookingSource() as $key=>$getBookingSourceList){
        $select = '';
        if($key == 0){
            $select = 'selected';
        }
        $id = $getBookingSourceList['id'];
        $name = ucfirst($getBookingSourceList['name']);
        $bookingSource .=   "<option value='$id' $select>$name</option>";
    }

    $html ='
            <div class="card">
                <div class="card-body">
                    <form action="">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-area1">
                                    <h4><i class="fas fa-caret-right"></i> Add Guest</h4>
                                </div>
                                <br />



                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="guestImgUpload">
                                            <label for="guestImg"><span>Upload</span></label>
                                            <input type="file" name="guestImg" id="guestImg">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form">
                                            <label for="">Name</label>
                                            <input type="text" placeholder="Name" class="form-control">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form">
                                                    <label for="">EMail</label>
                                                    <input type="text" placeholder="Mail" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form">
                                            <label for="">Phone</label>
                                            <input type="text" placeholder="Phone" class="form-control">
                                        </div>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form">
                                                    <label for="">Gender</label>
                                                    <div class="text-area">
                                                        <input type="radio" name="gender" value="male" id="male"> <label for="male">male</label>
                                                        <input type="radio" name="gender" value="female" id="female"> <label for="female">Female</label>
                                                        <input type="radio" name="gender" value="other" id="other"> <label for="other">Other</label>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form">
                                            <label for="">Mobile</label>
                                            <input type="text" placeholder="Name" class="form-control">
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form">
                                            <label for="">Address</label>
                                            <input type="text" placeholder="Contact" class="form-control">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form">
                                            <label for="">Counrty</label>
                                            <select class="form-control" name="" id="">
                                                <option value="" selected>Select country</option>
                                                <option value="">India</option>
                                                <option value="">Pk</option>
                                                <option value="">Uk</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form">
                                            <label for="">State</label>
                                            <input type="text" placeholder="India" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form">
                                            <label for="">City</label>
                                            <input type="text" placeholder="India" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-12">
                                <h4> <i class="fas fa-caret-right"></i>Other Imformation</h4>
                                <br />
                                <div class="form-1">
                                    <button class="btn btn-outline-dark">Clear</button>
                                    <button class="btn bg-gradient-primary">Save</button>
                                </div>
                            </div>

                        </div>
                        </form>
                </div>
            </div>
    ';

    echo $html;
}

if($type == 'checkRoomNumber'){
    $roomNum = safeData($_POST['roomNumber']);

    $currentDate = date('Y-m-d');

    $roomNumArry = getBookingData('',$roomNum,$currentDate);

    if(count($roomNumArry) > 0){
        $data = [
            'type'=>'popUp',
            'roomNo'=>$roomNum
        ];
    }else{
        $data = [
            'type'=>'false',
            'roomNo'=>''
        ];
    }

    

    echo json_encode($data);
}

if($type == 'showPopUpGuestDetail'){

    if($_POST['roomNum'] != ''){
        $roomNum = safeData($_POST['roomNum']);
        $bookDetailArry = getBookingData('',$roomNum)[0];
    }

    if($_POST['id'] != ''){
        $id = safeData($_POST['id']);
        $bookDetailArry = getBookingData('','','',$id)[0];
        $roomNum = $bookDetailArry['room_number'];
    }

    if($_POST['bId'] != ''){
        $bvId = safeData($_POST['bId']);
        $bid = getBookingIdByBVID($bvId);
        $bookDetailArry = getBookingData($bid)[0];
        $roomNum = $bookDetailArry['room_number'];
    }
    //  pr($bookDetailArry);
   
    
    $bid = $bookDetailArry['bid'];
    $roomId = $bookDetailArry['roomId'];
    $checkInStatus = $bookDetailArry['checkinstatus'];

    $bookingVId = $bookDetailArry['bookinId'];
    $reciptNo = $bookDetailArry['reciptNo'];
    $room_number = $bookDetailArry['room_number'];

    $checkIn = date('d M, y', strtotime($bookDetailArry['checkIn']));
    $checkOut = date('d M, y', strtotime($bookDetailArry['checkOut']));
    $add_on = date('d-M-Y', strtotime($bookDetailArry['add_on']));

    $night = getNightByTwoDates($bookDetailArry['checkIn'],$bookDetailArry['checkOut']);
    
    $roomName = strtoupper(getRoomNameType($bookDetailArry['roomId'])['header']);
    $roomDId = $bookDetailArry['roomDId'];

    $grossCharge = getBookingDetailById($bid)['totalPrice'];
    $avgPrice = $grossCharge / $night;
    $userPay = $bookDetailArry['userPay'];
    $guestPayable = $grossCharge - $userPay;


    // Check in status start
    $checkInStatusHtml = '';
    if($checkInStatus == 2){
        $checkInStatusHtml = '<button data-roomnum="'.$roomNum.'" id="checkInStatus" class="btn btn2 btn-outline-secondary mr4" data-tooltip-top="Checkout"><span><svg fill="none" width="15px" height="15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><g clip-path="url(#clip0)"><path d="M8.6778 9.3207v2.5994H10v-3.516l-3.4633-1.51c-.323-.1277-.6687-.1352-1.0142-.03-.3456.1052-.6236.3155-.8264.6236l-.7062 1.1118c-.278.5034-.6762 1.0292-1.217 1.3448-.5335.3155-1.1345.4733-1.788.4733v1.3748c.7512 0 1.5024-.1578 2.156-.4733.6536-.3155 1.2246-.8564 1.6904-1.3748l.4282 2.096-1.48 1.3823v5.2589H5.192v-4.132l1.4274-1.4649 1.2997 5.5969h1.48l-1.9758-9.849 1.2546.4882zm-5.4392-4.162c0 .834.6686 1.5026 1.5025 1.5026a1.4973 1.4973 0 001.5026-1.5026c0-.8339-.6762-1.5025-1.5026-1.5025-.8264 0-1.5025.6761-1.5025 1.5025z" fill="currentColor"></path><path d="M12.0204 10.0513l-3.9565 3.265a.236.236 0 00-.047.0816A.312.312 0 008 13.5a.312.312 0 00.0168.1021.236.236 0 00.0472.0816l3.9564 3.265c.1083.1224.2708.0117.2708-.1837V15h5.5421c.0917 0 .1667-.1049.1667-.2332v-2.5336c0-.1283-.075-.2332-.1667-.2332h-5.5421v-1.765c0-.1954-.1604-.3061-.2708-.1837z" fill="#F39406"></path><path d="M10 .119v.8929c0 .0655.0605.119.1345.119h8.5882V18H20V.4762C20 .2128 19.7597 0 19.4622 0h-9.3277C10.0605 0 10 .0536 10 .119z" fill="currentColor"></path></g><defs><clipPath id="clip0"><path fill="#fff" d="M0 0h20v20H0z"></path></clipPath></defs></svg></span></button>';
    }
    if($checkInStatus == 1){
        $checkInStatusHtml = '<button data-roomnum="'.$roomNum.'" id="checkInStatus" class="btn btn2 btn-outline-secondary mr4" data-tooltip-top="Checkin"><span><svg fill="none" width="15px" height="15px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><g clip-path="url(#clip0)"><path d="M2.307 9.3207v2.5994H.9848v-3.516l3.4633-1.51c.323-.1277.6687-.1352 1.0142-.03.3456.1052.6236.3155.8265.6236l.7061 1.1118c.278.5034.6762 1.0292 1.217 1.3448.5335.3155 1.1345.4733 1.7881.4733v1.3748c-.7513 0-1.5025-.1578-2.1561-.4733s-1.2246-.8564-1.6904-1.3748l-.4282 2.096 1.48 1.3823v5.2589H5.7929v-4.132l-1.4274-1.4649-1.2997 5.5969h-1.48l1.9759-9.849-1.2547.4882zm5.4392-4.162c0 .834-.6686 1.5026-1.5025 1.5026A1.4973 1.4973 0 014.741 5.1586c0-.8339.6762-1.5025 1.5026-1.5025.8264 0 1.5025.6761 1.5025 1.5025z" fill="currentColor"></path><path d="M13.9796 10.0513l3.9564 3.265a.2352.2352 0 01.0472.0816A.3141.3141 0 0118 13.5a.3141.3141 0 01-.0168.1021.2352.2352 0 01-.0472.0816l-3.9564 3.265c-.1083.1224-.2708.0117-.2708-.1837V15H8.1667C8.075 15 8 14.8951 8 14.7668v-2.5336c0-.1283.075-.2332.1667-.2332h5.5421v-1.765c0-.1954.1604-.3061.2708-.1837z" fill="#0068FF"></path><path d="M10 .119v.8929c0 .0655.0605.119.1345.119h8.5882V18H20V.4762C20 .2128 19.7597 0 19.4622 0h-9.3277C10.0605 0 10 .0536 10 .119z" fill="currentColor"></path></g><defs><clipPath id="clip0"><path fill="#fff" d="M0 0h20v20H0z"></path></clipPath></defs></svg></span></button>';
    }
    // Check in status end

    // Payment Btn Start
    if($guestPayable == 0){
        $paymentBtnHtml = '';
    }else{
        $paymentBtnHtml = '<button data-roomnum="'.$roomNum.'" id="paymentBtn" class="btn btn2 btn-outline-secondary" data-tooltip-top="Payment"><span><svg viewBox="64 64 896 896" width="15px" height="15px" focusable="false" data-icon="credit-card" fill="currentColor" aria-hidden="true"><path d="M928 160H96c-17.7 0-32 14.3-32 32v640c0 17.7 14.3 32 32 32h832c17.7 0 32-14.3 32-32V192c0-17.7-14.3-32-32-32zm-792 72h752v120H136V232zm752 560H136V440h752v352zm-237-64h165c4.4 0 8-3.6 8-8v-72c0-4.4-3.6-8-8-8H651c-4.4 0-8 3.6-8 8v72c0 4.4 3.6 8 8 8z"></path></svg></span></button>';
    }
    // Payment Btn end

    $maxAdult = getMaxAdultCountByRId($roomId);
    $bookGuest = count(getBookingDetailById($bid,$roomNum)['guest']);
    $addGouestBtn = '';
    if($maxAdult > $bookGuest){
        $addGouestBtn = '<div class="s25"></div><button id="addGustBtn" data-bookingId = "'.$bid.'" data-roomNum = "'.$room_number.'" class="btn btn-outline-primary">Add Guest</button>';
    }

    $guestList = '';
    $groupGuestName = '';
    $goupGuestImg = '';
    foreach(getBookingDetailById($bid,$roomNum)['guest'] as $key=>$guest){
        $gusetArray = getGuestDetail('','',$guest)[0];
        $guestName = ucfirst($gusetArray['name']);
        $guestImg = checkImg($gusetArray['image'], 'guest');
        $kayNum = $key + 1;
        $guestId = $gusetArray['id'];
        
        $guestList .= '
            <div class="group">
                                        
                <div class="box">
                    <img src="'.$guestImg.'">
                    <div class="caption">
                        <h5>'.$guestName.'</h5>
                        <p>'.$bookingVId.'/'.$kayNum.'|'.$roomNum.'-'.$kayNum.'</p>
                        <div class="editGuest" data-roomNum="'.$roomNum.'" data-bid="'.$bid.'" data-id="'.$guestId.'"><i class="far fa-edit"></i></div>
                    </div>
                </div>
                
            </div>
    ';
    
        if($gusetArray['owner'] == 1){
            $groupGuestName = ucfirst($gusetArray['name']);
            $goupGuestImg = checkImg($gusetArray['image'], 'guest');
        }
    }


    $guestList .= $addGouestBtn;


    

    $html = '
    
                <div class="row">
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-header">
                                <div class="booking">
                                    <i class="fab fa-goodreads"></i>
                                
                                    <div class="title">
                                        <div class="name"><h4>'.$groupGuestName.'</h4> <i class="fas fa-users"></i> </div>
                                        <div class="location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>india</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btnGroup">
                                    <button data-roomnum="'.$roomNum.'" class="btn btn-primary" id="reservations"><i class="fas fa-print"></i> Edit Reservation</button>
                                    '.$checkInStatusHtml.$paymentBtnHtml.'
                                    <button data-roomnum="'.$roomNum.'" id="printBtn" class="btn btn2 btn-outline-secondary" data-tooltip-top="Print"><span><svg viewBox="64 64 896 896" focusable="false" width="15px" height="15px" data-icon="printer" fill="currentColor" aria-hidden="true"><path d="M820 436h-40c-4.4 0-8 3.6-8 8v40c0 4.4 3.6 8 8 8h40c4.4 0 8-3.6 8-8v-40c0-4.4-3.6-8-8-8zm32-104H732V120c0-4.4-3.6-8-8-8H300c-4.4 0-8 3.6-8 8v212H172c-44.2 0-80 35.8-80 80v328c0 17.7 14.3 32 32 32h168v132c0 4.4 3.6 8 8 8h424c4.4 0 8-3.6 8-8V772h168c17.7 0 32-14.3 32-32V412c0-44.2-35.8-80-80-80zM360 180h304v152H360V180zm304 664H360V568h304v276zm200-140H732V500H292v204H160V412c0-6.6 5.4-12 12-12h680c6.6 0 12 5.4 12 12v292z"></path></svg></span></button>
                                </div>
                                
                                <div class="btnGroup">
                                    <button data-roomnum="'.$roomNum.'" id="checkInOutBtn" class="btn btn2 btn-outline-secondary" data-tooltip-top="Amend Stay"><span><svg width="15px" height="15px" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4643 2.67854H18.2143C18.6094 2.67854 18.9286 2.99774 18.9286 3.39282V11H17.3215V8.83925H2.67862V17.3214H12V18.9285H1.78576C1.39067 18.9285 1.07147 18.6093 1.07147 18.2143V3.39282C1.07147 2.99774 1.39067 2.67854 1.78576 2.67854H5.53576V1.24997C5.53576 1.15175 5.61612 1.0714 5.71433 1.0714H6.96433C7.06254 1.0714 7.1429 1.15175 7.1429 1.24997V2.67854H12.8572V1.24997C12.8572 1.15175 12.9375 1.0714 13.0358 1.0714H14.2858C14.384 1.0714 14.4643 1.15175 14.4643 1.24997V2.67854ZM2.67862 4.28568V7.3214H17.3215V4.28568H14.4643V5.35711C14.4643 5.45532 14.384 5.53568 14.2858 5.53568H13.0358C12.9375 5.53568 12.8572 5.45532 12.8572 5.35711V4.28568H7.1429V5.35711C7.1429 5.45532 7.06254 5.53568 6.96433 5.53568H5.71433C5.61612 5.53568 5.53576 5.45532 5.53576 5.35711V4.28568H2.67862Z" fill="currentColor"></path><path d="M19.3257 14.4617C19.3471 14.445 19.3643 14.4236 19.3762 14.3993C19.3881 14.3749 19.3943 14.3482 19.3943 14.3211C19.3943 14.294 19.3881 14.2672 19.3762 14.2429C19.3643 14.2185 19.3471 14.1971 19.3257 14.1804L16.1628 11.6804C16.0467 11.5889 15.8748 11.6715 15.8748 11.8211V13.4751H8.32127C8.22306 13.4751 8.1427 13.5554 8.1427 13.6537V14.9929C8.1427 15.0912 8.22306 15.1715 8.32127 15.1715L15.8726 15.1715V16.8211C15.8726 16.9706 16.0445 17.0532 16.1606 16.9617L19.3257 14.4617Z" fill="currentColor"></path></svg></span></button>
                                    <button data-roomnum="'.$roomNum.'" id="roomMoveBtn" class="btn btn2 btn-outline-secondary" data-tooltip-top="Room Move"><span><svg  width="15px" height="15px" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.7143 6.0625H16.4286C17.3754 6.06359 18.2832 6.42611 18.9527 7.07053C19.6222 7.71495 19.9989 8.58865 20 9.5V12H18.5714V9.5C18.5709 8.95316 18.3449 8.42887 17.9432 8.0422C17.5414 7.65552 16.9967 7.43805 16.4286 7.4375H10.7143V12H12V13.375H1.42857V16H0V4H1.42857V12H9.28571V7.4375C9.28609 7.07294 9.43672 6.72341 9.70455 6.46563C9.97238 6.20785 10.3355 6.06286 10.7143 6.0625Z" fill="currentColor"></path><path d="M5.25 7.28571C5.44072 7.28571 5.62715 7.34227 5.78573 7.44823C5.9443 7.55418 6.0679 7.70478 6.14088 7.88098C6.21387 8.05718 6.23296 8.25107 6.19576 8.43812C6.15855 8.62518 6.06671 8.79699 5.93185 8.93185C5.797 9.06671 5.62518 9.15855 5.43812 9.19576C5.25107 9.23296 5.05718 9.21387 4.88098 9.14088C4.70478 9.0679 4.55418 8.9443 4.44823 8.78573C4.34227 8.62715 4.28571 8.44072 4.28571 8.25C4.28606 7.99436 4.38776 7.74929 4.56852 7.56852C4.74929 7.38776 4.99436 7.28605 5.25 7.28571ZM5.25 6C4.80499 6 4.36998 6.13196 3.99997 6.37919C3.62996 6.62643 3.34157 6.97783 3.17127 7.38896C3.00097 7.8001 2.95642 8.2525 3.04323 8.68895C3.13005 9.12541 3.34434 9.52632 3.65901 9.84099C3.97368 10.1557 4.37459 10.3699 4.81105 10.4568C5.2475 10.5436 5.6999 10.499 6.11104 10.3287C6.52217 10.1584 6.87357 9.87004 7.12081 9.50003C7.36804 9.13002 7.5 8.69501 7.5 8.25C7.5 7.65326 7.26295 7.08097 6.84099 6.65901C6.41903 6.23705 5.84674 6 5.25 6V6Z" fill="currentColor"></path><path d="M19.3257 16.1407C19.3471 16.124 19.3643 16.1027 19.3762 16.0783C19.3881 16.0539 19.3943 16.0272 19.3943 16.0001C19.3943 15.973 19.3881 15.9462 19.3762 15.9219C19.3643 15.8975 19.3471 15.8762 19.3257 15.8595L16.1628 13.3595C16.0467 13.2679 15.8748 13.3505 15.8748 13.5001V15.1541H8.32127C8.22306 15.1541 8.1427 15.2345 8.1427 15.3327V16.672C8.1427 16.7702 8.22306 16.8505 8.32127 16.8505H15.8726V18.5001C15.8726 18.6496 16.0445 18.7322 16.1606 18.6407L19.3257 16.1407Z" fill="currentColor"></path></svg></span></button>
                                    <button data-roomnum="'.$roomNum.'" id="cancleReservation" class="btn btn2 btn-outline-secondary" data-tooltip-top="Reservation Cancel"><span><svg width="15px" height="15px" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M18.2143 2.6785h-3.75V1.25a.179.179 0 00-.1785-.1786h-1.25a.179.179 0 00-.1786.1786v1.4285H7.1429V1.25a.179.179 0 00-.1786-.1786h-1.25a.179.179 0 00-.1785.1786v1.4285h-3.75a.7135.7135 0 00-.7143.7143v14.8215c0 .395.3192.7142.7143.7142h16.4285a.7135.7135 0 00.7143-.7142V3.3928a.7135.7135 0 00-.7143-.7143zm-.8928 14.6429H2.6786V8.8392h14.6429v8.4822zm-14.6429-10V4.2857h2.8572V5.357a.179.179 0 00.1785.1786h1.25a.1791.1791 0 00.1786-.1786V4.2857h5.7143V5.357a.179.179 0 00.1786.1786h1.25a.179.179 0 00.1785-.1786V4.2857h2.8572v3.0357H2.6786z" fill="currentColor"></path><g clip-path="url(#clip0)"><circle cx="15" cy="15" r="3" fill="#fff"></circle><path d="M15 10c-2.7612 0-5 2.2388-5 5s2.2388 5 5 5 5-2.2388 5-5-2.2388-5-5-5zm1.846 6.8996l-.7366-.0034L15 15.5737l-1.1083 1.3214-.7377.0033a.0888.0888 0 01-.0893-.0892.0929.0929 0 01.0212-.0581l1.452-1.7299-1.452-1.7288a.0935.0935 0 01-.0212-.058.0896.0896 0 01.0893-.0893l.7377.0033L15 14.471l1.1083-1.3214.7366-.0034a.0889.0889 0 01.0893.0893.0927.0927 0 01-.0213.058l-1.4497 1.7288 1.4509 1.7299a.093.093 0 01.0212.0581.0896.0896 0 01-.0893.0893z" fill="#FF5353"></path></g><defs><clipPath id="clip0"><path fill="#fff" transform="translate(10 10)" d="M0 0h10v10H0z"></path></clipPath></defs></svg></span></button>
                                </div>
                            </div>

                            <div class="card-body">
                                <table width="100%">

                                    <tr>
                                        <td><p><small>Reservation Number</small><br/><span>'.$reciptNo.'</span></p></td>                                    
                                        <td align="right"><p><small>Voucher Number</small><br/><span>'.$bookingVId.'</span></p></td>                                    
                                    </tr>

                                    <tr>
                                        <td colspan="2" class="confirme paymentStatus"><p><small>Status</small><br/> <span>Confirmed Booking</span></p></td>                                                         
                                    </tr>

                                    <tr>
                                        <td><p><small>Arrival Date</small><br/><span>'.$checkIn.'</span></p></td>                                    
                                        <td align="right"><p><small>Departure Date</small><br/><span>'.$checkOut.'</span></p></td>                                    
                                    </tr>

                                    <tr>
                                        <td><p><small>Booking Date</small><br/><span>'.$add_on.'</span></p></td>                                    
                                        <td align="right"><p><small>Room Type</small><br/><span>'.$roomName.'</span></p></td>                                    
                                    </tr>

                                    <tr>
                                        <td><p><small>Room Number</small><br/><span>'.$roomNum.'</span></p></td>                                    
                                        <td align="right"><p><small>Avg. Daily Rate</small><br/><span>Rs '.number_format($avgPrice,2).'</span></p></td>                                    
                                    </tr>
                                
                                </table>
                            </div>
                            <div class="card-footer">
                                <table width="100%">
                                    <tr>
                                        <td><p>Total</p></td>                                    
                                        <td align="right"><p>RS '.number_format($grossCharge,2).'</p></td>                                    
                                    </tr> 
                                    <tr>
                                        <td><p>Paid</p></td>                                    
                                        <td align="right"><p>RS '.number_format($userPay,2).'</p></td>                                    
                                    </tr> 
                                    <tr>
                                        <td><p>amount</p></td>                                    
                                        <td align="right"><p>RS '.number_format($guestPayable,2).'</p></td>                                    
                                    </tr>                                
                                </table>
                            </div>
                        </div>
                        

                    </div>
                    <div class="col-md-6" style="position:relative">
                        
                            
                        <div class="bookingGuestList bookingRoomList">
                            <h4>Guest List</h4>

                            '.$guestList.'

                        </div>

                        <div class="bookingOtherDetail" id="bookingOtherDetail">
                            
                        </div>

                       
                    </div>

                </div>


    ';


    echo $html;
}

if($type == 'checkRoomCheckIn'){
    $roomNum = safeData($_POST['roomNumber']);
    $bookDetailArry = getBookingData('',$roomNum)[0];
    $checkInStatus = $bookDetailArry['checkinstatus'];
    if($checkInStatus == 1){
        $sql = "update bookingdetail set checkinstatus = '2' where room_number = '$roomNum'";
    }
    
    if($checkInStatus == 2){
        $sql = "update bookingdetail set checkinstatus = '3' where room_number = '$roomNum'";
    }

    if(mysqli_query($conDB,$sql)){
        echo 1;
    }else{
        echo 0;
    }
}

if($type == 'paymentBtnClick'){
    $roomNum = safeData($_POST['roomNumber']);
    $paymentMwthod = '';
    foreach(getPaymentTypeMethod() as $paymentList){
        $data = $paymentList['name'];
        $dataId = $paymentList['id'];
        $paymentMwthod .= "<option value='$dataId'>$data</option>";
    }
    
    $html = '
            <div class="paymentBlock">
                <form id="paymentBtnClickForm">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Amount</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter amount" name="amount" required>
                    </div>
                    <input type="hidden" value="'.$roomNum.'" name="roomNum" id="guestRoomNum">
                    <input type="hidden" value="paymentBtnClickFormSubmit" name="type">
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="">Payment Method</label>
                            <select class="form-control" name="paymentMethod" required>
                                <option disabled selected>Select*</option>
                                '.$paymentMwthod.'
                            </select>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-6"><span class="btn btn-outline-secondary removeRoomView">Cancel</span></div>
                    <div class="col-6 flexEnd"><button type="submit" class="btn bg-gradient-primary">Submit</button></div>
                    </div>

                </form>
            </div>
    ';

    echo $html;
}

if($type == 'printBtnClick'){
    $roomNum = safeData($_POST['roomNumber']);
    $paymentMwthod = '';
    foreach(getPaymentTypeMethod() as $paymentList){
        $data = $paymentList['name'];
        $dataId = $paymentList['id'];
        $paymentMwthod .= "<option value='$dataId'>$data</option>";
    }
    
    $html = '
            <div class="paymentBlock">
                <h4>Print Voucher </h4>
                <form>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="chooseVoucher">Choose</label>
                            <select id="chooseVoucher" class="form-control">
                                <option>Guest</option>
                                <option>Hotel</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-6"><button class="btn btn-outline-secondary removeRoomView">Cancel</button></div>
                    <div class="col-6 flexEnd"><button type="submit" class="btn bg-gradient-primary">Download</button></div>
                    </div>

                </form>
            </div>
    ';

    echo $html;
}

if($type == 'checkInOutBtnClick'){
    $roomNum = safeData($_POST['roomNumber']);
    $bookingArray = getBookingData('',$roomNum)[0];

    $checkIn = $bookingArray['checkIn'];
    $checkOut = $bookingArray['checkOut'];
    
    $html = '
            <div class="paymentBlock">
                <h4>Check In Change</h4>
                <form id="checkInOutBtnClickForm">
                    <div class="row mb-4">
                        <div class="col-6">
                            <label for="checkIn">Check In</label>
                            <input type="date" id="checkIn" class="form-control" value="'.$checkIn.'" name="checkIn" required>
                        </div>
                        <div class="col-6">
                            <label for="checkOut">Check Out</label>
                            <input type="date" id="checkOut" class="form-control" value="'.$checkOut.'" name="checkOut" required>
                        </div>
                    </div>

                    <input type="hidden" value="'.$roomNum.'" name="roomNum" id="checkInRoomNum">
                    <input type="hidden" value="checkInOutBtnClickFormSubmit" name="type">

                    <div class="row">
                    <div class="col-6"><span class="btn btn-outline-secondary removeRoomView">Cancel</span></div>
                    <div class="col-6 flexEnd"><button type="submit" class="btn bg-gradient-primary">Update</button></div>
                    </div>

                </form>
            </div>
    ';

    echo $html;
}

if($type == 'roomMoveBtnClick'){
    $roomNum = safeData($_POST['roomNumber']);
    $bookingArray = getBookingData('',$roomNum)[0];
    $roomId = $bookingArray['roomId'];
    $roomDId = $bookingArray['roomDId'];
    $checkIn = $bookingArray['checkIn'];
    $checkOut = $bookingArray['checkOut'];

    $roomTypeHtml = '';
    $roomNumHtml = '';
    $ratePlaneHtml = '';

    foreach(getRoomType() as $roomTypeList){
        $name = $roomTypeList['header'];
        $roomTypeId = $roomTypeList['id'];
        if($roomTypeId == $roomId){
            $roomTypeHtml .= "<option selected value='$roomTypeId'>$name</option>";
        }else{
            $roomTypeHtml .= "<option value='$roomTypeId'>$name</option>";
        }
    }

    if(count(getRoomNumber('','',1,$roomId,$checkIn)) > 0){
        foreach(getRoomNumber('','',1,$roomId,$checkIn) as $roomNumList){
            $num = $roomNumList['roomNo'];
            $numId = $roomNumList['id'];
            if($roomTypeId == $roomId){
                $roomNumHtml .= "<option selected value='$num'>$num</option>";
            }else{
                $roomNumHtml .= "<option value='$num'>$num</option>";
            }
        }
    }else{
        $roomNumHtml = "<option value='0'>No Room</option>";
    }

    foreach(getRateType($roomId,'','1') as $ratePlaneList){
        $ratePlaneName = $ratePlaneList['title'];
        $ratePlaneid = $ratePlaneList['id'];
        $ratePlaneHtml .= "<option value='$ratePlaneid'>$ratePlaneName</option>";
    }
    
    $pageName = getPageName($_SERVER['PHP_SELF']);
    
    $html = '
            <div class="paymentBlock">
                <h4>Room change</h4>
                <form id="roomMoveBtnClickForm" data-page="'.$pageName.'">

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="currentRoom">Current Room</label>
                            <input class="form-control" type="text" disabled value="'.$roomNum.'" id="currentRoom" name="currentRoomNum">
                        </div>
                    </div>

                    <input type="hidden" value="'.$roomNum.'" name="roomNum" id="moveRoomNum">
                    <input type="hidden" value="roomMoveBtnClickFormSubmit" name="type">

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="chooseRoomForMove">Room </label>
                            <select class="form-control" id="chooseRoomForMove" name="roomType">
                                '.$roomTypeHtml.'
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="chooseRatePlaneForMove">Rate Plane</label>
                            <select class="form-control" id="chooseRatePlaneForMove" name="ratePlane">
                                '.$ratePlaneHtml.'
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="chooseRoomTypeForMove">Room Number</label>
                            <select class="form-control" id="chooseRoomTypeForMove" name="roomNumber">
                                '.$roomNumHtml.'
                            </select>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-6"><span class="btn btn-outline-secondary removeRoomView">Cancel</span></div>
                    <div class="col-6 flexEnd"><button type="submit" class="btn bg-gradient-primary">Move</button></div>
                    </div>

                </form>
            </div>
    ';

    echo $html;
}

if($type == 'cancleReservationClick'){
    $roomNum = safeData($_POST['roomNumber']);
    $paymentMwthod = '';
    foreach(getPaymentTypeMethod() as $paymentList){
        $data = $paymentList['name'];
        $dataId = $paymentList['id'];
        $paymentMwthod .= "<option value='$dataId'>$data</option>";
    }
    
    $html = '
            <div class="paymentBlock">
                <h4>Print Voucher </h4>
                <form>

                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="chooseVoucher">Choose</label>
                            <select id="chooseVoucher" class="form-control">
                                <option>Guest</option>
                                <option>Hotel</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-6"><span class="btn btn-outline-secondary removeRoomView">Cancel</span></div>
                    <div class="col-6 flexEnd"><button type="submit" class="btn bg-gradient-primary">Download</button></div>
                    </div>

                </form>
            </div>
    ';

    echo $html;
}

if($type == 'chooseRoomForMoveClick'){
    $roomId = safeData($_POST['roomId']);
    $data = '';
    foreach(getRoomNumber('','',1,$roomId) as $roomTypeList){
        $num = $roomTypeList['roomNo'];
        $numId = $roomTypeList['id'];

        $data .= "<option value='$num'>$num</option>";
    }

    echo $data;
} 

if($type == 'chooseRAtePlaneForMoveClick'){
    $roomId = safeData($_POST['roomId']);
    $data = '';
    foreach(getRateType($roomId,'','1') as $ratePlaneList){
        $rateName = $ratePlaneList['title'];
        $rateId = $ratePlaneList['id'];

        $data .= "<option value='$rateId'>$rateName</option>";
    }

    echo $data;
}

if($type == 'paymentBtnClickFormSubmit'){
    
    $amount = safeData($_POST['amount']);
    $roomNum = safeData($_POST['roomNum']);
    $paymentMethod = safeData($_POST['paymentMethod']);
    // $paymentType = safeData($_POST['paymentType']);
    $paymentType = '';

    $bookDetailArry = getBookingData('',$roomNum)[0];
    $bid = $bookDetailArry['bid'];

    $sql = "update booking set userPay = '$amount', paymethodId ='$paymentMethod', paytypeId='$paymentType' where id = '$bid'";

    if(mysqli_query($conDB,$sql)){
        echo 1;
    }else{
        echo 0;
    }

}

if($type == 'checkInOutBtnClickFormSubmit'){
 
    $checkIn = safeData($_POST['checkIn']);
    $checkOut = safeData($_POST['checkOut']);
    $roomNum = safeData($_POST['roomNum']);

    $bookDetailArry = getBookingData('',$roomNum)[0];
    $bid = $bookDetailArry['bid'];

    $sql = "update booking set checkIn = '$checkIn', checkOut ='$checkOut' where id = '$bid'";

    if(mysqli_query($conDB,$sql)){
        echo 1;
    }else{
        echo 0;
    }

}

if($type == 'roomMoveBtnClickFormSubmit'){

    $oldRoomNum = safeData($_POST['roomNum']);
    $roomType = safeData($_POST['roomType']);
    $roomNumber = safeData($_POST['roomNumber']);
    $ratePlane = safeData($_POST['ratePlane']);

    $bookDetailArry = getBookingData('',$oldRoomNum)[0];
    $bid = $bookDetailArry['bid'];
    

    $sql = "update bookingdetail set room_number = '$roomNumber', roomId ='$roomType', roomDId='$ratePlane' where bid = '$bid' and room_number = '$oldRoomNum'";

    if(mysqli_query($conDB,$sql)){
        echo 1;
        mysqli_query($conDB, "update guest set roomnum = '$roomNumber' where bookId = '$bid' and roomnum = '$oldRoomNum'");
    }else{
        echo 0;
    }

}



if($type == 'addGuestResurvationForm'){
    $html = '
        
    ';
}

if($type == 'excelImportSubmit'){
    if(!empty($_FILES['csvFile']['name'])){
        $csvFile = fopen($_FILES['csvFile']['tmp_name'], 'r');
        fgetcsv($csvFile);
        
        while(($line = fgetcsv($csvFile)) !== FALSE){

            $checkIn   = $line[1];
            $checkOut  = $line[2];
            $addOn  = $line[3];
            $userPay = $line[6];
            $payAtHotel = $line[7];
            $bookingVendor = $line[11];
            $vendorBookinId = $line[12];

            $guestName = $line[8];
            
            $status = $line[3];
            $status = $line[3];

            $fileArray[] = $line;
        }
        
        pr($fileArray);
    }
}


?>