<?php

include ('../constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');

$type = '';

if(isset($_POST['type'])){
    $type = $_POST['type'];
}

if($type == 'loadReview'){
  
    $si = 0;
    $pagination = '';
    
    $sql = "select * from guest_review where pid = '0'";
        
    
    $limit_per_page = 15;
    
    $page = '';
    if(isset($_POST['page_no'])){
        $page = $_POST['page_no'];
    }else{
        $page = 1;
    }
    
    
    $offset = ($page -1) * $limit_per_page;
    
    $sql .= " ORDER BY id DESC ";
    // $sql .= " ORDER BY id DESC limit {$offset}, {$limit_per_page}";
    
    $html = '<table class="table">
                <thead>
                    <tr>
                        <th>Guest name</th>
                        <th>Email Id</th>
                        <th>Comment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';

    $query = mysqli_query($conDB, $sql);
    $si = $si + ($limit_per_page *  $page) - $limit_per_page;
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $si ++;
            $rid = $row['id'];
            $gId = $row['guestId'];

            if($gId != ''){
                $guestDetailArry = getGuestDetail('','',$gId)[0];
                $name = $guestDetailArry['name'];
                $email = $guestDetailArry['email'];
            }else{
                $name = $row['name'];
                $email = $row['email'];
            }
            $msg = $row['msg'];

            $html .= '<tr id="guestNameCheckRow'.$si.'" class="guestCheckRow">

                            <td>  <button class="showReplayOnReview" data-rid="'.$rid.'">'.$name.'</button> </td>
                            <td>'.$email.'</td>
                            <td>'.$msg.'</td>
                            <td class="iconCon ">
                                <div class="tooltipCon"> 
                                    <i class="fas fa-ellipsis-v"></i>
                                    
                                    <ul class="tooltipBody">
                                        <li><a href="javascript:void(0)" data-tooltip-top="Edit"><i class="far fa-edit"></i></a></li>
                                        <li><a href="javascript:void(0)" data-tooltip-top="Delete"><i class="far fa-trash-alt"></i></a></li>
                                        <li><a href="javascript:void(0)" data-tooltip-top="Detail Log"><i class="fas fa-info"></i></a></li>
                                    </ul>
                                </div>
                            </td>
                            
                        </tr>';
            

        }
    }else{
       
    }

    $html .= '</table>';

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

if($type == 'showReplay'){
    $rid = $_POST['rid'];

    $sql = mysqli_query($conDB, "select * from guest_review where pid = '$rid'");

    
    $html = 'No Replay';
    $loopHtml = '';
    if(mysqli_num_rows($sql) > 0){
       while( $row = mysqli_fetch_assoc($sql)){
        $msg = $row['msg'];
        $addOn = date('d-M', strtotime($row['addOn']));

        $loopHtml .= '
            <table class="table">
                <tr>
                    <td>Replay on '.$addOn.'</td>
                    <td align="right">'.$msg.'</td>
                </tr>
            </table>
        ';
       }
       $html = $loopHtml;

    }

   
    echo $html;
}




?>