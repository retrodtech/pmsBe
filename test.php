<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'add_to_room.php');

// pr(generateRecipt());
// getRoomNumber($rNo='', $status = '', $rid='', $checkIn ='', $checkOut = '',$ridRes = '')
pr(SingleRoomPriceCalculator(1, 1, 2, 0 , 1, 1, 1001, 0 , 0));


?>