<?php

class add_to_room{
    function addroom($rid,$room,$adult,$child,$night,$rdid,$checkIn,$checkout,$key=''){
        $rid = trim($rid);

        if($key != ''){
            $rdid = "$rdid-$key";
        }

        $_SESSION['room'][$rdid]['room']=$room;
        $_SESSION['room'][$rdid]['adult']=$adult;
        $_SESSION['room'][$rdid]['child']=$child;
        $_SESSION['room'][$rdid]['night']=$night;
        $_SESSION['room'][$rdid]['roomId']=$rid;
        $_SESSION['room'][$rdid]['checkIn']=$checkIn;
        $_SESSION['room'][$rdid]['checkout']=$checkout;

    }
    function updateroom($rid,$room){
        if(isset($_SESSION['room'][$rid])){
            $_SESSION['room'][$rid]['room']=$room;
        }
    }
    function removeroom($rdid){
        if(isset($_SESSION['room'][$rdid])){
            unset($_SESSION['room'][$rdid]);
        }
    }
    function emptyroom(){
        unset($_SESSION['room']);
    }
    function checkInDateUpdate($date,$date2,$key=''){
        $_SESSION['checkIn'] = $date;
        $_SESSION['checkout'] = $date2;

        $_SESSION['room'][$key]['checkIn'] = $date;
            $_SESSION['room'][$key]['checkout'] = $date2;
        
    }
    function totalroom(){
        if(isset($_SESSION['room'])){
            return count($_SESSION['room']);
        }else{
            return 0;
        }
    }
}



?>