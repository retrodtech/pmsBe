<footer class="footer footer-dark position-relative">

    <div class="footer_content">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="caption">
                        <div class="logo" style="width: 120px; margin-bottom: 10px;"><img src="<?php echo FRONT_SITE_IMG.'logo.png' ?>" alt="<?php echo hotelDetail()['name'] ?> logo"></div>
                        <p id="footerDescCaption"><?php 
                        $strlen = strlen(hotelDetail()['description']);
                            if(strlen(hotelDetail()['description']) > 180){
                                echo substr(hotelDetail()['description'], 0, 180).'<span id="footerDescReadMoreBtn">... <span style="color: #abd9ff;cursor: pointer;">Read More</span></span>';
                                echo '<span id="footerDescReadLessCaption" style="display:none">'. substr(hotelDetail()['description'], 180, $strlen) . '<span id="footerDescReadLessBtn" style="cursor: pointer;color: #abd9ff;"> Read Less</span></span>';
                            }else{
                                echo hotelDetail()['description'];
                            }
                        
                        
                        ?></p>
                        
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="booking_detail">
                        <h4>Booking info</h4>
                        <ul class="booking_info">
                            <li style="display: block;">
                                <i class="far fa-calendar-check"></i> <b><?php echo hotelDetail()['checkIn'] ?></b>
                            </li>
                            <li style="display: block;">
                                <i class="far fa-calendar-times"></i> <b><?php echo hotelDetail()['checkOut'] ?></b>
                            </li>
                            <li style="display: block;">
                                <a href="mailto:<?php echo hotelDetail()['email'] ?>"><i class="far fa-envelope-open"></i> <b><?php echo hotelDetail()['email'] ?></b></a>
                            </li>
                            <li style="display: block;">
                                <a href="tel:<?php echo hotelDetail()['primaryphone'] ?>"><i class="fas fa-headset"></i> <b><?php echo hotelDetail()['primaryphone'] ?></b></a>
                               
                            </li>
                        </ul>
                        <br/>
                        <ul class="social">
                            <li style="display: inline-block;"><a href="https://www.facebook.com/jamindarspalacepuriodisha" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li style="display: inline-block;"><a href="https://www.instagram.com/jamindarspalace" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <h4>Payments Acceptable</h4>
                    <ul style="padding-top: 20px;">
                        <li style="display: inline-block;"><img style="width: 40px;" src="<?php echo FRONT_SITE_IMG ?>icon/master.png" alt=""></li>
                        <li style="display: inline-block;"><img style="width: 40px;" src="<?php echo FRONT_SITE_IMG ?>icon/visa.png" alt=""></li>
                        <li style="display: inline-block;"><img style="width: 40px;" src="<?php echo FRONT_SITE_IMG ?>icon/rupay.png" alt=""></li>
                        <li style="display: inline-block;"><img style="width: 40px;" src="<?php echo FRONT_SITE_IMG ?>icon/gpay.png" alt=""></li>
                        <li style="display: inline-block;"><img style="width: 40px;" src="<?php echo FRONT_SITE_IMG ?>icon/phonepe.png" alt=""></li>
                    </ul>
                    
                    <h4 style="margin-top:15px">Quick Link</h4>
                    <ul style="padding-top: 20px;">
                        <li class="footer_link" style="display: block;"><a href="<?php echo FRONT_BOOKING_SITE ?>/hotel-policy.php" target="_blank"><span>Hotel Policy</span></a></li>
                        <li class="footer_link" style="display: block;"><a href="<?php echo FRONT_BOOKING_SITE ?>/cancel-policy.php" target="_blank"><span>Cancel Policy</span></a></li>
                        <li class="footer_link" style="display: block;"><a href="<?php echo FRONT_BOOKING_SITE ?>/refund-policy.php" target="_blank"><span>Refund Policy</span></a></li>
                    </ul>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="divider mt-3"></div>

    <div class="footer-copyright py-3">
        <div class="container">
            <div class="d-md-flex justify-content-between align-items-center py-3 text-center text-md-left">

                <div class="copyright-text">©<?php echo date('Y') ?> All Rights Reserved by <a href="<?php echo hotelDetail()['url'] ?>" target="blank"> <?php echo hotelDetail()['name'] ?>.</a> ||
                    Powered By <a target="blank" href="https://retrodtech.com"> Retrod.</a></div>
            </div>
        </div>
    </div>
</footer>

<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/623426eaa34c2456412ba294/1fudrg1g6';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>



