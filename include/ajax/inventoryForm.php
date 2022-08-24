<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$type = '';

if(isset($_POST['type'])){
    $type = $_POST['type'];
}

if($type == 'updateRoom'){
    $form = getDataBaseDate2($_POST['from']);
    $to = getDataBaseDate2($_POST['to']);
    $oneDay = strtotime('1 day 30 second', 0);
    
    $datediff = strtotime($to) - strtotime($form);
    $output = round($datediff / (60 * 60 * 24));
    
    $room = $_POST['room'];
    $updateId = $_POST['updateId'];
    
    for($i=0; $i<= $output; $i ++){
        $date = date('Y-m-d',strtotime($form) + ($oneDay * $i));
        inventoryRoomUpdate($updateId, $room, $date,'1');        
        
    }
    
   $_SESSION['SuccessMsg'] = "Successfull Update Room";
   
    
}

if($type == 'updateRate'){
    // pr($_POST);
    $form = getDataBaseDate2($_POST['from']);
    $to = getDataBaseDate2($_POST['to']);
    $oneDay = strtotime('1 day 30 second', 0);
    
    $datediff = strtotime($to) - strtotime($form);
    $output = round($datediff / (60 * 60 * 24));

    $price = $_POST['sglprice'];
    $price2 = $_POST['dblprice'];
    $adult = $_POST['adult'];
    $child = $_POST['child'];
    $rdid = $_POST['updateId'];
    $rid= $_POST['updateRId'];

    for($i=0; $i<= $output; $i ++){
        $date = date('Y-m-d',strtotime($form) + ($oneDay * $i));
        echo inventoryRateUpdate($rid, $rdid, $price,$price2,$date,$child,$adult);
        
    }
    
    
    $_SESSION['SuccessMsg'] = "Successfull Update Price";
}

if($type == 'blockId'){
    // pr($_POST);
    $form = getDataBaseDate2($_POST['from']);
    $to = getDataBaseDate2($_POST['to']);
    $oneDay = strtotime('1 day 30 second', 0);
    
    $datediff = strtotime($to) - strtotime($form);
    $output = round($datediff / (60 * 60 * 24));
    $updateRId = $_POST['updateId'];

    

    for($i=0; $i<= $output; $i ++){
        $date = date('Y-m-d',strtotime($form) + ($oneDay * $i));
        
        inventoryRoomUpdate($updateRId, '0', $date,'0');      
        
    }    
    
    $_SESSION['SuccessMsg'] = "Successfull Block Rooms";
    
}

if($type == 'reloadRoom'){
    $updateRoom = $_POST['updateRoom'];
    $sql = "delete from inventory where room_id = '$updateRoom' and type = 'room'";
   
    if(mysqli_query($conDB,$sql)){
        $_SESSION['SuccessMsg'] = "Successfull Reload Room";
    }
}

if($type == 'reloadRate'){
    $updateRateId = $_POST['updateRoom'];
    $updateRateDetail = $_POST['updateRoomDetail'];
    $sql = "delete from inventory where room_detail_id = '$updateRateDetail' and type = 'room_detail' and room_id = '$updateRateId'";
    if(mysqli_query($conDB,$sql)){
        $_SESSION['SuccessMsg'] = "Successfull Reload Rate";
    }
}

if($type == 'inventoryUpdate'){
    $roomNo = $_POST['roomNo'];
    $roomId = $_POST['roomId'];
    $roomDate = $_POST['roomDate'];

    inventoryRoomUpdate($roomId, $roomNo, $roomDate,'1');      

    // $_SESSION['SuccessMsg'] = "Successfull Update Room";

}

if($type == 'inlineRoomPrice'){
    $roomPrice = $_POST['roomPrice'];
    $roomId = $_POST['roomId'];
    $roomDate = $_POST['roomDate'];
    $roomDId = $_POST['roomDId'];
    $AdultDate = $_POST['AdultDate'];
   
    $child = '';
    $adult = '';

    if($AdultDate == 1){
        $roomPrice2 = '';
    }

    if($AdultDate == 2){
        $roomPrice2 = $roomPrice;
        $roomPrice = '';
    }
    
    
    inventoryRateUpdate($roomId, $roomDId, $roomPrice,$roomPrice2,$roomDate,$child,$adult);

}


if($type == 'viewRateForm'){
    $rdid = $_POST['id'];
    $rid = $_POST['rid'];

    $checkRoomParent = buildSGLView($rid,$rdid);

    $html = '<div class="row">';

    if($checkRoomParent[0]['doublePrice'] == 0){
        $html .='<div class="col-md-12">
                    <div class="form-group">
                        <label for="">Price *:</label>
                        <input type="number" name="price" class="form-control">
                    </div>
                    
                </div>';

    }


    if($checkRoomParent[0]['doublePrice'] != 0){
        $html .='<div class="col-md-6">
                    <div class="form-group">
                        <label for="">SGL Price *:</label>
                        <input type="number" name="sglprice" class="form-control">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">DBL Price *:</label>
                        <input type="number" name="dblprice" class="form-control">
                    </div>
                    
                </div>
                ';
    }

    // DBL
    // <input type="hidden" value="'.$adult.'" name="nadult[]">
    //                 <input type="hidden" value="'.$id.'" name="rdid[]">
    //                 <input type="hidden" value="'.$rid.'" name="rId[]">

    


    


    $html .= '
    <div class="row p0">
        <div class="form-group col-md-6">
            <label for="">E. Adult:</label>
            <input type="number" name="adult" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="">E. Child:</label>
            <input type="number" name="child" class="form-control">
        </div>
    </div>
    </div>';

    $html .= '<input type="hidden" value="updateRate" id="updateType" name="type">
            <input type="hidden" value='.$rdid.' name="updateId">
            <input type="hidden" value='.$rid.' name="updateRId">';

    echo $html;
}

?>