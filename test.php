<?php

include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');
include (SERVER_INCLUDE_PATH.'add_to_room.php');

// pr(generateRecipt());
// getRoomNumber($rNo='', $status = '', $rid='', $checkIn ='', $checkOut = '',$ridRes = '')
pr(getRoomNumber('',1,1,'2022-08-27','2022-08-28','res')[0])


?>