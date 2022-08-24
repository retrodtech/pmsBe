

<header id="navBar" class="header-static navbar-sticky navbar-light">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            
            <div class="flex">
                <div class="left_side">
                    <a style="display: flex;justify-content: center;align-items: center;" class="logo" href="<?php echo hotelDetail()['url'] ?>" target="blank">
                        <img style="width: 100%;height: auto;" src="<?php echo FRONT_SITE_IMG.hotelDetail()['logo'] ?>" alt="">
                    </a>
                </div>
                <div class="right_side">
                    <div class="contact_section">
                        <div class="box">
                            <a href="tel:<?php echo hotelDetail()['primaryphone'] ?>">
                                <span class="fas fa-phone-volume icon"></span>
                                <span><?php echo hotelDetail()['primaryphone'] ?></span>
                            </a>
                        </div>
                        <div class="box">
                            <a href="mailto: <?php echo hotelDetail()['email'] ?>">
                                <span class="far fa-envelope icon"></span>
                                <span><?php echo hotelDetail()['email'] ?></span>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            

        </div>
    </nav>
    <div class="side_content">
        <div class="logo"><img src="<?php echo FRONT_SITE_IMG.'logo.png' ?>" alt="<?php echo hotelDetail()['name'] ?> logo"></div>
        <div class="caption">
            <div class="top_contact">
                <span><a href="tel:<?php echo hotelDetail()['primaryphone'] ?>"><b>Phone:</b> <?php echo hotelDetail()['primaryphone'] ?></a></span>
                <span><a href="mailto:<?php echo hotelDetail()['email'] ?>"><b>Email:</b> <?php echo hotelDetail()['email'] ?></a></span>
            </div>
            <h6><?php echo hotelDetail()['description'] ?></h6>
        </div>
    </div>
</header>