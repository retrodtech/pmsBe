<?php

class add_to_package{
    function addPackage($pid,$rid,$rdid,$cid,$adult,$child,$checkIn,$night){
        
        $_SESSION['package']['pid']=$pid;
        $_SESSION['package']['rid']=$rid;
        $_SESSION['package']['rdid']=$rdid;
        $_SESSION['package']['cid']=$cid;
        $_SESSION['package']['adult']=$adult;
        $_SESSION['package']['child']=$child;
        $_SESSION['package']['checkIn']=$checkIn;
        $_SESSION['package']['pickup']='';
        $_SESSION['package']['night']=$night;
    }
    function updateRoom($room){
        if(isset($_SESSION['package'])){
            $_SESSION['package']['rid']=$room;
        }
    } 

    function updateCar($car){
        if(isset($_SESSION['package'])){
            $_SESSION['package']['cid']=$car;
        }
    }

    function updatePickUp($price){
        if(isset($_SESSION['package'])){
            $_SESSION['package']['pickup']=$price;
        }
    }

    function updateNight($night){
        if(isset($_SESSION['package'])){
            $_SESSION['package']['night']=$night;
        }
    } 
    
    function updateAdult($n){
        if(isset($_SESSION['package'])){
            $_SESSION['package']['adult']=$n;
        }
    }
    
    function updateChild($n){
        if(isset($_SESSION['package'])){
            $_SESSION['package']['child']=$n;
        }
    }
    
    function emptyPackage(){
        unset($_SESSION['package']);
    }
}



?>