<?php

define('SITE_NAME', 'Retrod');
define('BOOK_GENERATE', 'jamindars_');
define('QP_GENERATE', 'qpjamindars_');

define('HOTEL_LOGIN', 'Retrod');

define('COMM_PRICE', '12');
define('QPCOMM_PRICE', '11');

define('RETROD_GST', '21AALCR2582C1ZN ');
define('RETROD_PAN', 'AALCR2582C');
define('RETROD_TAN', 'BBNR03307D');

define('SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].'/pms');

define('SA_SERVER_PATH', SERVER_PATH.'/superadmin');
define('FO_SERVER_PATH', SERVER_PATH.'/frontoffice');
define('WB_SERVER_PATH', SERVER_PATH.'/web-builder');
define('BE_SERVER_PATH', SERVER_PATH.'/web-builder');
define('WS_SERVER_PATH', $_SERVER['DOCUMENT_ROOT'].'/bePms');
define('WS_BE_SERVER_PATH',  $_SERVER['DOCUMENT_ROOT'].'/bePms');

define('SERVER_INCLUDE_PATH', WS_BE_SERVER_PATH.'/include/');

define('SA_SERVER_SCREEN_PATH', SA_SERVER_PATH.'/screen/');
define('FO_SERVER_SCREEN_PATH', FO_SERVER_PATH.'/screen/');
define('WB_SERVER_SCREEN_PATH', WB_SERVER_PATH.'/screen/');
define('WS_SERVER_SCREEN_PATH', WS_SERVER_PATH.'/screen/');
define('WS_BE_SERVER_SCREEN_PATH', WS_BE_SERVER_PATH.'/screen/');

define('SERVER_IMG', SERVER_PATH.'/img/');

define('SERVER_BOOKING_PATH', SERVER_PATH);
define('SERVER_ADMIN_PATH', SERVER_PATH);



define('SERVER_ADMIN_LOGO', SERVER_BOOKING_PATH.'/admin/img/admin/');
define('SERVER_ROOM_IMG', SERVER_BOOKING_PATH.'/admin/img/room/');
define('SERVER_HERO_IMG', SERVER_BOOKING_PATH.'/admin/img/hero/');



define('FRONT_SITE','http://localhost/pms');

define('SA_FRONT_SITE',FRONT_SITE.'/superadmin');
define('FO_FRONT_SITE',FRONT_SITE.'/frontoffice');
define('WB_FRONT_SITE',FRONT_SITE.'/web-builder');
define('BE_FRONT_SITE',FRONT_SITE.'/booking');
define('WS_FRONT_SITE','http://localhost/bePms');
define('WS_BE_FRONT_SITE',WS_FRONT_SITE);

define('WS_FRONT_SITE_BE_URL',WS_FRONT_SITE);

define('FRONT_SITE_IMG', FRONT_SITE.'/img/');

define('WS_FRONT_SITE_IMG', FRONT_SITE.'/img/');

define('FRONT_BOOKING_SITE', FRONT_SITE);



define('FRONT_ADMIN_SITE', FRONT_BOOKING_SITE);
define('FRONT_ADMIN_SITE_INCLUDE', FRONT_ADMIN_SITE.'/include');
define('FRONT_ADMIN_SITE_AJAX', FRONT_ADMIN_SITE_INCLUDE.'/ajax/');
define('FRONT_SITE_ADMIN_LOGO', FRONT_BOOKING_SITE.'/admin/img/admin/');
define('FRONT_SITE_ROOM_IMG', FRONT_BOOKING_SITE.'/img/room/');
define('FRONT_SITE_HERO_IMG', FRONT_BOOKING_SITE.'/admin/img/hero/');



?>