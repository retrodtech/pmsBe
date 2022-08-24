<?php
include ('include/constant.php');
include (SERVER_INCLUDE_PATH.'db.php');
include (SERVER_INCLUDE_PATH.'function.php');


if(!isset($_POST['status'])){
    redirect('index.php');
    die();
}


// pr($_POST);


?>

<!doctype html>
<html lang="en">

<head>
    <?php include(SERVER_BOOKING_PATH.'/screen/head.php') ?>
    <title>Thank You</title>
       <style>
        .download {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .download a {
            padding: 10px 25px;
            background: transparent;
            margin-bottom: 15px;
            color: #1164a9;
            border-radius: 3px;
            border: 2px solid #a3c5e1;
            transition: .5s ease-in-out;
        }

        .download a:hover {
            border: 2px solid #a3c5e1;
            background: #a3c5e1;
            color: black;
        }
        .innerpage-banner.left .breadcrumb {
            right: auto;
            top: 118%;
            width: auto;
            margin-top: -10px;
        }
    </style>
</head>

<body>


    <?php include(SERVER_BOOKING_PATH.'/screen/navbar.php') ?>

    
    <div class="innerpage-banner left bg-overlay-dark-7 py-7" style="background:url(images/07.jpg) repeat; background-size:cover;">
        <div class="container">
            <div class="row all-text-white">
                <div class="col-md-6 align-self-center">
                    <h1 class="innerpage-title">Thank You</h1>
                   <nav aria-label="breadcrumb" >
                        <ol class="breadcrumb" style="background-color: transparent;">
                            <li class="breadcrumb-item"><a href="<?php echo FRONT_BOOKING_SITE ?>"><i class="ti-home"></i> Home</a></li>
                            <li class="breadcrumb-item">Thank You</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6" style="display: flex;justify-content: end;">
                    <canvas width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    
    <?php
    
        if($_POST['status'] == 'success'){
            
            $bid = $_POST['txnid'];
             
             $email = $_POST['email'];
             
             $payid = $_POST['easepayid'];
            

             mysqli_query($conDB,"update quickpay set paymentStatus='complete',paymentId='$payid' where orderId='$bid'");

        
             $sql = mysqli_query($conDB, "select * from quickpay where orderId='$bid'");
             $quickPayrow = mysqli_fetch_assoc($sql);
             
             $name = $quickPayrow['name'];
             $phone = $quickPayrow['phone'];
             $email = $quickPayrow['email'];
             $room = $quickPayrow['room'];
             $room_id = $quickPayrow['room_id'];
             $qickPayNote = $quickPayrow['qickPayNote'];
             $paymentStatus = $quickPayrow['paymentStatus'];
             $amount = $quickPayrow['amount'];
             $roomPrice = $quickPayrow['roomPrice'];
             
             $checkIn = $quickPayrow['checkIn'];
             $checkOut = $quickPayrow['checkOut'];
             
             $gst = getGSTPrice($roomPrice);
             $oid = $quickPayrow['id'];
             $totalPrice = $roomPrice + $gst;
             $hotel_email = hotelDetail()['email'];
             send_email($email,$name,$hotel_email,RETROD_BOOKING_EMAIL,quickPayEmail($oid),'Your Quick Payment Success');
             $roomContent = '';
             $payDetail = '';
             if($room != 0){
                 $roomContent = '
                    
                    <div class="card mb-4 mb-3 mt-3">
                        <h5 class="card-header d-flex align-items-center justify-content-between"><span>Payble <small
                                    class="text-secondary">Detail</small></span></h5>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 d-flex mb-3">
                                   <dl class="row">
                                        <dt class="col-5">Room:</dt>
                                        <dd class="col-7">'.getRoomHeaderById($room).'</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-5">Check In:</dt>
                                        <dd class="col-7">'.$checkIn.'</dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-5">Check Out:</dt>
                                        <dd class="col-7">'.$checkOut.'</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                 ';
                 
                 $payDetail = '
                 
                    <tr>
                        <td class="bookex">Total Price:</td>
                        <td>'.$amount.'</td>
                    </tr>
                 
                 ';
             }

             unset($_SESSION['QPOID']);
             unset($_SESSION['razorpay_order_id']);
             
             
             ?>
             
             
             <section class="pt80 pb80 booking-section login-area thanksYou">
                    <div class="container">
                        <div class="row">
            
            
            
            
                            <div class="col-lg-8 col-md-12 col-sm-12 mb-3">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="download">
                                            <a href="<?php echo FRONT_BOOKING_SITE ?>/download_invoice.php?qpid=<?php echo $oid ?>">
                                                <i class="fa fa-download"></i> Download PDF
                                            </a>
                                        </div>
                                        <div class="login-box Booking-box">
                                            <div class="login-top">
                                                <h3>Confirm Pay</h3>
                                                <p>Thank You. Your Advance Pay is Confirmed Now.</p>
                                            </div>
            
            
            
            
            
            
                                            <div class="login-top cardInfo">
                                                <h3>Advance Pay Information</h3>
                                                <p>Booking for <?php echo ucfirst($name) ?> at <?php echo SITE_NAME ?></p>
                                            </div>
            
            
            
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td class="bookex">Booking number:</td>
                                                        <td><b><?php echo $bid ?></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bookex">Guest Name:</td>
                                                        <td><?php echo ucfirst($name) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bookex">E-mail:</td>
                                                        <td><?php echo $email ?></td>
                                                    </tr>
                                                    
                                                    <?php echo $payDetail ?>
                                                    
                                                    
                                                    <tr>
                                                        <td class="bookex">Payment status:</td>
                                                        <td><?php if($paymentStatus == 'complete'){
                                                            echo 'Success';
                                                        }else{
                                                            echo 'Failed';
                                                        } ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="bookex">Request:</td>
                                                        <td><?php echo $quickPayrow['qickPayNote'] ?></td>
                                                    </tr>
            
            
                                                </tbody>
                                            </table>
                                            
                                            <?php echo $roomContent ?>
            
            
                                        </div>
            
                                    </div>
                                </div>
                            </div>
            
            
            
            
            
            
            
            
            
            
            
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                
                                <div class="listing-item ">
                                    
                                    <div class="listing-item">
                                        
                                    <article class="TravelGo-category-listing fl-wrap">
                                        <div class="TravelGo-category-content fl-wrap title-sin_item">
                                            <div class="TravelGo-category-content-title fl-wrap NeedHelp">
                                                <div class="TravelGo-category-content-title-item">
                                                    <h3 class="title-sin_map"><a href="hotel-detailed.html">Need Help?</a></h3>
                                                    <div class="TravelGo-category-location fl-wrap"></div>
                                                </div>
                                            </div>
                                            <div class="NeedhelpSection">
                                                <P>We would be more than happy to help you. Our team advisor are 24/7 at your service to help you.</P>
                                                <ul>
                                                    <li><span><i class="fas fa-phone-volume"></i></span> <?php echo hotelDetail()['primaryphone'] ?></li>
                                                    <li style="    word-break: break-all;"><span><i class="far fa-envelope"></i></span> <?php echo hotelDetail()['email'] ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </article>
                                    
                                </div>
                            </div>
                                
                            </div>
            
                        </div>
                    </div>
                </section>
             
             <?php 
        }
             
                unset($_SESSION['QPOID']);  
             
             ?>

    
    <?php include(SERVER_BOOKING_PATH.'/screen/footer.php') ?>

    
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/popper.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/functions.js" type="text/javascript"></script>
    <script src="js/owl.carousel.min.js" type="text/javascript"></script>
    <script src="js/slick.js" type="text/javascript"></script>
    <script src="js/swiper.min.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>
    <script src="js/jquery.fancybox.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="js/isotope.pkgd.min.js" type="text/javascript"></script>
    <script src="js/imagesloaded.pkgd.min.js" type="text/javascript"></script>
    
    <script src='https://unpkg.com/zdog@1/dist/zdog.dist.min.js'></script>
    <script src='https://unpkg.com/animejs@3.0.1/lib/anime.min.js'></script>
    
    <script>
            $('#nav_togle').on('click', function () {
                $('header .side_content').toggleClass('active');
            });
            
            const {
  Illustration, Ellipse, Rect, Shape, Group, Anchor,
} = Zdog;

// set up the illustration within the existing canvas element
const illustration = new Illustration({
  element: 'canvas',
  dragRotate: true,
});

// below the star draw a circle with a fill and no stroke, for the shadow
const shadow = new Ellipse({
  addTo: illustration,
  diameter: 100,
  stroke: false,
  fill: true,
  color: 'hsla(45, 100%, 58%, 0.4)',
  translate: { x: 50, y: 100 },
  rotate: { x: Math.PI / 1.7 },
});

// include an anchor point for the star
// ! position the star atop the anchor, to have the rotation occur around this point
const starAnchor = new Anchor({
  addTo: illustration,
  translate: { y: 100 },
  rotate: { z: Math.PI / 10 },
});

// draw a star in a group element positioned atop the anchor point
const starGroup = new Group({
  addTo: starAnchor,
  translate: { x: -70, y: -170 }, // -70 to center the 140 wide shape
});

// draw the path describing the star
new Shape({
  addTo: starGroup,
  path: [
    { x: 0, y: 45 },
    { x: 45, y: 45 },
    { x: 70, y: 0 },
    { x: 95, y: 45 },
    { x: 140, y: 45 },
    { x: 105, y: 80 },
    { x: 120, y: 130 },
    { x: 70, y: 105 },
    { x: 20, y: 130 },
    { x: 35, y: 80 },
    { x: 0, y: 45 },
  ],
  stroke: 40,
  color: 'hsl(45, 100%, 58%)',
});
// within the path include a rectangle to remove the gap between the center of the star and its stroke
new Rect({
  addTo: starGroup,
  width: 40,
  height: 50,
  stroke: 40,
  translate: { x: 70, y: 70 },
  color: 'hsl(45, 100%, 58%)',
});

// include a group for the eyes, positioned halfway through the height of the star
const eyesGroup = new Group({
  addTo: starGroup,
  translate: { x: 70, y: 72.5, z: 20 },
});

// add black circles describing the contour of the eyes, and either end of the star
const eye = new Ellipse({
  addTo: eyesGroup,
  diameter: 5,
  stroke: 15,
  translate: { x: -32.5 },
  color: 'hsl(0, 0%, 0%)',
});
eye.copy({
  translate: { x: 32.5 },
});

// add an anchor point for the white part of the eyes
// by later translating the white part of the eyes, the rotation allows to have the circle rotate around the anchor point
const leftEyeAnchor = new Anchor({
  addTo: eyesGroup,
  translate: { x: -32.5, z: 0.5 },
});
const leftEye = new Ellipse({
  addTo: leftEyeAnchor,
  diameter: 1,
  stroke: 5,
  color: 'hsl(0, 100%, 100%)',
  translate: { x: -3.5 },
});

// copy the left anchor for the right side
const rightEyeAnchor = leftEyeAnchor.copyGraph({
  translate: { x: 32.5, z: 0.5 },
});

// include an anchor point for the mouth
// by centering the mouth around the anchor and scaling the anchor itself, the change in size occurs from the center of the mouth
const mouthAnchor = new Anchor({
  addTo: starGroup,
  translate: { x: 70, y: 95, z: 20 },
  scale: 0.8,
});
// draw a mouth with a line and arc commands
const mouth = new Shape({
  addTo: mouthAnchor,
  path: [
    { x: -8, y: 0 },
    { x: 8, y: 0 },
    {
      arc: [
        { x: 4, y: 6 },
        { x: 0, y: 6 },
      ],
    },
    {
      arc: [
        { x: -4, y: 6 },
        { x: -8, y: 0 },
      ],
    },
  ],
  stroke: 10,
  color: 'hsl(358, 100%, 65%)',
});

illustration.updateRenderGraph();

/* to animate the star, change the transform property as follows

|variableName|transform|valueRange|
|---|---|---|
|starAnchor|rotate.z|[Math.PI/10, -Math.PI/10]|
|leftIrisAnchor && rightIrisAnchor|rotate.z|[0, Math.PI/2]|
|mouthAnchor|scale|[0.8, 1.2]|
|shadow|translate.x|[50, -50]|
*/

// ! I am positive there are much better ways to achieve this animation, but this is my take using anime.js
// I am still a newbie when it comes to animation
// create an object describing the values for the different elements
const starObject = {
  star: Math.PI / 10,
  shadow: 50,
  mouth: 0.8,
  eyes: 0
}

// set up a repeating animation which constantly updates the illustration and updates the desired transform properties according to the object's values
const timeline = anime.timeline({
  duration: 1100,
  easing: 'easeInOutQuart',
  direction: 'alternate',
  loop: true,
  update: () => {
    starAnchor.rotate.z = starObject.star;
    shadow.translate.x = starObject.shadow;
    mouth.scale = starObject.mouth;
    leftEyeAnchor.rotate.z = starObject.eyes;
    rightEyeAnchor.rotate.z = starObject.eyes;

    illustration.updateRenderGraph();
  }
});

// animate the star with a slightly more pronounced easing function
timeline.add({
  targets: starObject,
  star: -Math.PI/10,
  easing: 'easeInOutQuint',
});
// have the shadow follow with a small delay
timeline.add({
  targets: starObject,
  delay: 20,
  shadow: -50,
}, '-=1100')

// with a smaller duration and slightly postponed, animate the mouth and the eyes
timeline.add({
  targets: starObject,
  mouth: 1.2,
  duration: 300,
}, '-=800');

timeline.add({
  targets: starObject,
  eyes: Math.PI / 2,
  duration: 900,
}, '-=1000');

    </script>
    
</body>


</html>