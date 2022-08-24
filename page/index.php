

<?php include(WS_BE_SERVER_SCREEN_PATH.'/navbar.php') ?>
    
    
    <section class="p-0 parallax-bg" style="z-index: 6;position: relative;">
        <div id="demo" class="carousel slide" data-ride="carousel">

           
            <div class="carousel-inner">
                <?php
                
                    $sql = mysqli_query($conDB, "select * from herosection where hotelId = '$hotelId' limit 5");
                    if(mysqli_num_rows($sql)>0){
                        $count = 0;
                        while($row = mysqli_fetch_assoc($sql)){
                            $img = WS_FRONT_SITE_IMG.'/hero/'.$row['img'];
                            $count ++;
                            if($count == 1){
                                $active = 'active';
                            }else{
                                $active = '';
                            }
                            echo "
                                <div class='carousel-item $active'>
                                    <img src='$img' alt='' width='1100' height='500'>
                                </div>
                            ";
                        }
                    }
                
                ?>
                
            </div>

            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>

        <section class="mt-lg-n9 mt-sm-0 pb-0 z-index-9 booking-search">
            <div class="container ">
                <div class="row border-radius-3" style="justify-content: center;">
                    <div class="col-md-10 np" >
                        <div class="feature-box h-100">
                            <div class="tab_container">
                                <input id="tab1" type="radio" name="tabs" checked>
                                <label for="tab1">
                                    <!-- <span>Find Your </span> 
                                    <i class="fas fa-utensils"></i>
                                    <span>Hotel</span> -->
                                </label>
                                <section id="content1" class="tab-content">
                                    <form method="post" id="searcfForm">
                                        <div class="row">

                                            <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 padding8">
                                                <div class="form-group"> <span class="far fa-calendar-alt"></span>
                                                    <input class="form-control" type="text" id="check_in_date" name="check_in_date" placeholder="Check In Date" autocomplete="off" style="padding: 8px 15px 8px 32px;" value="<?php echo date('d/m/Y',$current_date) ?>">
                                                    <div id="checkinError" class="formError"></div>
                                                </div>
                                            </div>

                                            <input type="hidden" value="<?php echo $hotelId ?>" name="hotelId">

                                            <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 padding8">
                                                <div class="form-group"> <span class="far fa-calendar-alt"></span>
                                                    <input class="form-control" type="text" id="check_out_date" name="check_out_date" placeholder="Check Out Date" autocomplete="off" style="padding: 8px 15px 8px 32px;" value="<?php echo date('d/m/Y',$current_date + (1 * $one_day)) ?>">
                                                        <div id="checkoutError" class="formError"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12 padding8">
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-lg primary_btn" type="submit" style="padding: 0 0;">Search</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </section>


    <section class="Categories pt80 pb60 " id="roomSection">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8">
                    <p class="subtitle text-secondary nopadding">Stay and eat like a local</p>
                    <h1 class="paddtop1 font-weight lspace-sm">Guest's Favourite Picks</h1>
                </div>
            </div>
            <div class="row" id="load_search">

                <?php
               
                    $sql = mysqli_query($conDB, "select room.*,roomratetype.id as rdid from room,roomratetype where hotelId = '$hotelId' and  room.id=roomratetype.room_id group by(room.id) ORDER BY `roomratetype`.`price` asc");
                    
                    
                    if(mysqli_num_rows($sql) > 0){
                        while($room_rows = mysqli_fetch_assoc($sql)){
                            $nAdult = $room_rows['noAdult'];
                            
                            $price = getRoomPriceById($room_rows['id'], $room_rows['rdid'] , $nAdult, date('Y-m-d'));
                            $rid = $room_rows['slug'];
                            $rheader = $room_rows['header'];
                            $facrId = $room_rows['faceId'];
                            
                            $imgArr = getImageById($room_rows['id']);
                            $advancePayPrice = settingValue()['advancePay'];

                            $url = WS_BE_FRONT_SITE."/room/$rid" ;
                            $faceHtml = '';
                            if($facrId != 0){
                                $faceImg = FRONT_SITE_IMG.'icon/facing/'.getFacingDetailById($facrId)['img'];
                                $faceHtml = "<div class='view'><img src='$faceImg'></div>";
                            }

                            $veiwDetailHtml = '
                                <div class="viewContent">
                                    <div class="item">
                                        <svg version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 230 119" style="enable-background:new 0 0 230 119;width:20px" >
                                            <g id="XMLID_2_">
                                                <g id="XMLID_10_">
                                                    <path id="XMLID_63_" d="M225.1,62.9c-0.8,0-1.5-0.3-2-1c-25.8-34.3-65.2-54-108.1-54c-42.9,0-82.3,19.7-108.1,54
                                                        c-0.8,1.1-2.4,1.3-3.5,0.5c-1.1-0.8-1.3-2.4-0.5-3.5c26.8-35.6,67.6-56,112-56c44.5,0,85.3,20.4,112.1,56c0.8,1.1,0.6,2.7-0.5,3.5
                                                        C226.1,62.7,225.6,62.9,225.1,62.9z"/>
                                                </g>
                                                <g id="XMLID_11_">
                                                    <path id="XMLID_62_" d="M115,117.8c-44.5,0-85.3-20.4-112-56c-0.8-1.1-0.6-2.7,0.5-3.5c1.1-0.8,2.7-0.6,3.5,0.5
                                                        c25.8,34.3,65.2,54,108.1,54c42.9,0,82.3-19.7,108.1-54c0.8-1.1,2.4-1.3,3.5-0.5c1.1,0.8,1.3,2.4,0.5,3.5
                                                        C200.3,97.4,159.5,117.8,115,117.8z"/>
                                                </g>
                                            </g>
                                            <g id="XMLID_16_">
                                                <path d="M138.3,60.4c0,12.9-10.4,23.3-23.3,23.3S91.7,73.2,91.7,60.4c0-6.8,3-13,7.7-17.3c1,2,3.7,3.2,6.5,2.6
                                                    c3.3-0.6,5.5-3.3,5-5.9c-0.2-0.8-0.6-1.5-1.1-2.1c1.7-0.4,3.4-0.6,5.2-0.6C127.9,37.1,138.3,47.5,138.3,60.4z"/>
                                            </g>
                                            <g id="XMLID_8_">
                                                <path id="XMLID_4_" d="M115,104.2c-24.1,0-43.8-19.6-43.8-43.8S90.9,16.6,115,16.6s43.8,19.6,43.8,43.8S139.1,104.2,115,104.2z
                                                    M115,21.6c-21.4,0-38.8,17.4-38.8,38.8c0,21.4,17.4,38.8,38.8,38.8s38.8-17.4,38.8-38.8C153.8,39,136.4,21.6,115,21.6z"/>
                                            </g>
                                        </svg>
                                        <strong>250</strong>
                                    </div>
                                    <div class="item">
                                        <svg x="0px" y="0px" viewBox="0 0 153.7 150.7" style="enable-background:new 0 0 153.7 150.7; width:20px">
                                            <g id="XMLID_64_">
                                                <g id="XMLID_82_">
                                                    <path id="XMLID_148_" d="M57.9,16.3H38.4c-1.4,0-2.5-1.1-2.5-2.5s1.1-2.5,2.5-2.5h19.5c1.4,0,2.5,1.1,2.5,2.5S59.3,16.3,57.9,16.3
                                                        z"/>
                                                </g>
                                                <g id="XMLID_80_">
                                                    <path id="XMLID_147_" d="M122.8,54.3c-1.4,0-2.5-1.1-2.5-2.5V22.1c0-3.2-2.6-5.8-5.8-5.8h-11.7c-1.4,0-2.5-1.1-2.5-2.5
                                                        s1.1-2.5,2.5-2.5h11.7c6,0,10.8,4.8,10.8,10.8v29.7C125.3,53.2,124.2,54.3,122.8,54.3z"/>
                                                </g>
                                                <g id="XMLID_78_">
                                                    <path id="XMLID_146_" d="M90.4,16.3H70.3c-1.4,0-2.5-1.1-2.5-2.5s1.1-2.5,2.5-2.5h20.1c1.4,0,2.5,1.1,2.5,2.5S91.8,16.3,90.4,16.3
                                                        z"/>
                                                </g>
                                                <g id="XMLID_77_">
                                                    <path id="XMLID_145_" d="M61.1,133.3H15.4c-6,0-10.8-4.8-10.8-10.8V22.1c0-6,4.8-10.8,10.8-10.8H26c1.4,0,2.5,1.1,2.5,2.5
                                                        s-1.1,2.5-2.5,2.5H15.4c-3.2,0-5.8,2.6-5.8,5.8v100.4c0,3.2,2.6,5.8,5.8,5.8h45.7c1.4,0,2.5,1.1,2.5,2.5S62.5,133.3,61.1,133.3z"
                                                        />
                                                </g>
                                                <g id="XMLID_67_">
                                                    <path id="XMLID_142_" d="M32.2,25.6c-4.8,0-8.7-3.9-8.7-8.7v-6.7c0-4.8,3.9-8.7,8.7-8.7s8.7,3.9,8.7,8.7v6.7
                                                        C40.9,21.7,37,25.6,32.2,25.6z M32.2,6.6c-2,0-3.7,1.7-3.7,3.7v6.7c0,2,1.7,3.7,3.7,3.7s3.7-1.7,3.7-3.7v-6.7
                                                        C35.9,8.2,34.3,6.6,32.2,6.6z"/>
                                                </g>
                                                <g id="XMLID_69_">
                                                    <path id="XMLID_139_" d="M64.1,25.6c-4.8,0-8.7-3.9-8.7-8.7v-6.7c0-4.8,3.9-8.7,8.7-8.7s8.7,3.9,8.7,8.7v6.7
                                                        C72.8,21.7,68.9,25.6,64.1,25.6z M64.1,6.6c-2,0-3.7,1.7-3.7,3.7v6.7c0,2,1.7,3.7,3.7,3.7s3.7-1.7,3.7-3.7v-6.7
                                                        C67.8,8.2,66.2,6.6,64.1,6.6z"/>
                                                </g>
                                                <g id="XMLID_70_">
                                                    <path id="XMLID_136_" d="M96.6,25.6c-4.8,0-8.7-3.9-8.7-8.7v-6.7c0-4.8,3.9-8.7,8.7-8.7s8.7,3.9,8.7,8.7v6.7
                                                        C105.3,21.7,101.4,25.6,96.6,25.6z M96.6,6.6c-2,0-3.7,1.7-3.7,3.7v6.7c0,2,1.7,3.7,3.7,3.7s3.7-1.7,3.7-3.7v-6.7
                                                        C100.3,8.2,98.7,6.6,96.6,6.6z"/>
                                                </g>
                                                <g id="XMLID_68_">
                                                    <path id="XMLID_133_" d="M35.6,62.8c-4,0-7.3-3.3-7.3-7.3s3.3-7.3,7.3-7.3s7.3,3.3,7.3,7.3S39.6,62.8,35.6,62.8z M35.6,53.3
                                                        c-1.2,0-2.3,1-2.3,2.3s1,2.3,2.3,2.3s2.3-1,2.3-2.3S36.9,53.3,35.6,53.3z"/>
                                                </g>
                                                <g id="XMLID_72_">
                                                    <path id="XMLID_130_" d="M65,62.8c-4,0-7.3-3.3-7.3-7.3s3.3-7.3,7.3-7.3s7.3,3.3,7.3,7.3S69,62.8,65,62.8z M65,53.3
                                                        c-1.2,0-2.3,1-2.3,2.3s1,2.3,2.3,2.3s2.3-1,2.3-2.3S66.2,53.3,65,53.3z"/>
                                                </g>
                                                <g id="XMLID_73_">
                                                    <path id="XMLID_127_" d="M35.6,98.5c-4,0-7.3-3.3-7.3-7.3s3.3-7.3,7.3-7.3s7.3,3.3,7.3,7.3S39.6,98.5,35.6,98.5z M35.6,89
                                                        c-1.2,0-2.3,1-2.3,2.3c0,1.2,1,2.3,2.3,2.3s2.3-1,2.3-2.3C37.9,90,36.9,89,35.6,89z"/>
                                                </g>
                                                <g id="XMLID_71_">
                                                    <path id="XMLID_124_" d="M103.8,146.6c-25.4,0-46.1-20.7-46.1-46.1s20.7-46.1,46.1-46.1s46.1,20.7,46.1,46.1
                                                        S129.2,146.6,103.8,146.6z M103.8,59.4c-22.6,0-41.1,18.4-41.1,41.1s18.4,41.1,41.1,41.1s41.1-18.4,41.1-41.1
                                                        S126.4,59.4,103.8,59.4z"/>
                                                </g>
                                                <g id="XMLID_74_">
                                                    <path id="XMLID_121_" d="M103.1,108.5c-4.1,0-7.4-3.3-7.4-7.4s3.3-7.4,7.4-7.4s7.4,3.3,7.4,7.4S107.2,108.5,103.1,108.5z
                                                        M103.1,98.7c-1.3,0-2.4,1.1-2.4,2.4s1.1,2.4,2.4,2.4s2.4-1.1,2.4-2.4S104.5,98.7,103.1,98.7z"/>
                                                </g>
                                                <g id="XMLID_75_">
                                                    <path d="M103.1,96.5L103.1,96.5c-1,0-1.8-0.8-1.8-1.8V72.3c0-1,0.8-1.8,1.8-1.8l0,0c1,0,1.8,0.8,1.8,1.8v22.3
                                                        C104.9,95.7,104.1,96.5,103.1,96.5z"/>
                                                </g>
                                                <g id="XMLID_76_">
                                                    <path d="M132.5,102.7h-21.9c-0.9,0-1.7-0.7-1.7-1.7v0c0-0.9,0.7-1.7,1.7-1.7h21.9c0.9,0,1.7,0.7,1.7,1.7v0
                                                        C134.1,101.9,133.4,102.7,132.5,102.7z"/>
                                                </g>
                                            </g>
                                        </svg>
                                        <strong>150</strong>
                                    </div>
                                </div>
                            ';
                            $headerHtml = "<h3><a href='$url'>$rheader</a></h3>";
                            if($price >= $advancePayPrice){
                               
                                $priceHtml = '';
                            }else{
                                
                                $priceHtml = "<div class='priceBox'><h4>Rs  <strong>$price</strong> </h4> $veiwDetailHtml</div>";
                            }

                            $roomDetailSection = '
                            
                                <ul class="inlineIcon">
                                    <li>
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 28 24"> <g fill="none" fill-rule="evenodd"> <g fill="#000" fill-rule="nonzero"> <g> <g> <path d="M27.402 22.052l-.91-3.196c-.042-.147-.175-.256-.337-.277-.162-.02-.32.053-.402.185l-1.055 1.698-2.635-1.442V10.65c0-.02-.003-.04-.007-.06.004-.016.006-.031.007-.046 0-.134-.076-.258-.2-.325L14.118 5.98V3.133h2.1c.164 0 .31-.092.373-.232.062-.141.028-.303-.088-.41L14 .15c-.157-.146-.412-.146-.57 0l-2.504 2.34c-.115.107-.15.269-.087.41.062.14.21.232.372.232h2.1V5.98l-7.745 4.238c-.123.067-.2.19-.2.325l.007.046c-.004.02-.006.04-.006.06v8.37L2.73 20.463l-1.054-1.699c-.082-.132-.24-.204-.403-.184-.161.02-.294.129-.336.276l-.91 3.197c-.058.201.07.407.286.461l3.423.85c.157.04.325-.014.424-.135.099-.121.11-.286.028-.418l-1.053-1.697 2.634-1.441 7.741 4.235.015.006c.022.01.045.02.069.027l.024.007c.063.016.129.016.191 0l.023-.007c.024-.007.047-.016.07-.027.004-.003.01-.003.014-.006l7.744-4.235 2.634 1.442-1.053 1.697c-.073.116-.073.26-.001.377.072.116.205.188.35.188.035 0 .07-.004.103-.012l3.423-.85c.215-.054.343-.26.286-.462zM13.714.951l1.531 1.429h-3.061l1.53-1.43zM.91 21.88l.556-1.953L3.002 22.4.91 21.88zM13.714 6.632l7.148 3.91-7.148 3.91-7.147-3.91 7.147-3.91zM6.172 11.2l7.139 3.905v7.82L6.172 19.02V11.2zm7.946 11.725v-7.82l7.138-3.905v7.82l-7.138 3.905zm10.309-.524l1.535-2.473.557 1.953-2.092.52z" transform="translate(-135 -838) translate(135 710) translate(0 128)"></path> </g> </g> </g> </g> </svg>
                                        </div>
                                        <span>80m2</span>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"> <g fill="none" fill-rule="evenodd"> <g fill="#1A1A1A" fill-rule="nonzero"> <g> <g> <g> <path d="M22.738 6.143h-.103V2.172C22.635.974 21.661 0 20.463 0H3.537C2.339 0 1.365.974 1.365 2.172v3.97h-.103C.566 6.143 0 6.71 0 7.405V8.3c0 .39.177.738.455.97v8.146c0 .41.197.774.5 1.005v1.27c0 .318.187.594.456.725v3.006c0 .32.26.579.579.579h1.365c.319 0 .579-.26.579-.58v-.876h16.132v.877c0 .32.26.579.58.579h1.364c.32 0 .58-.26.58-.58v-2.55c.268-.131.455-.407.455-.725V18.42c.303-.23.5-.595.5-1.005V9.269c.278-.232.455-.58.455-.97v-.895c0-.695-.566-1.261-1.262-1.261zM2.068 2.172c0-.81.659-1.469 1.469-1.469h16.926c.81 0 1.469.659 1.469 1.469v3.97h-1.618V2.194c0-.21-.106-.4-.284-.51-.179-.11-.397-.12-.585-.027l-1.315.658c-.158.079-.334.12-.51.12h-3.277c-.176 0-.353-.041-.51-.12l-1.316-.658c-.164-.082-.353-.084-.517-.009-.164-.075-.353-.073-.517.01l-1.316.657c-.157.079-.334.12-.51.12H6.38c-.176 0-.352-.041-.51-.12l-1.315-.658c-.188-.093-.406-.084-.585.027-.178.11-.284.3-.284.51v3.95H2.068V2.172zm19.818 21.125H20.77v-1.105c0-.194-.157-.351-.351-.351H3.582c-.194 0-.351.157-.351.351v1.105H2.114v-2.8h7.229c.657 0 1.318.054 1.967.162l.719.12c.686.114 1.387.172 2.082.172h7.775v2.346zm.455-3.152c0 .057-.046.103-.103.103H14.11c-.657 0-1.319-.054-1.967-.162l-.719-.12c-.686-.115-1.387-.173-2.082-.173h-7.58c-.058 0-.104-.046-.104-.103v-1.015l.058.001h3.23c.194 0 .352-.157.352-.351 0-.194-.158-.352-.352-.352h-3.23c-.308 0-.559-.25-.559-.558V9.556c.034.003.069.005.104.005h18.521c-.834 1.261-2.395 2.398-4.472 3.242-2.464 1-5.434 1.53-8.59 1.53-.193 0-.35.157-.35.352 0 .194.157.351.35.351 3.246 0 6.308-.547 8.855-1.582 2.409-.979 4.183-2.355 5.028-3.893h.53c-.395 2.35-1.251 4.467-2.426 5.984-1.229 1.589-2.7 2.428-4.25 2.428H6.22c-.194 0-.351.158-.351.352 0 .194.157.351.351.351h16.062l.058-.001v1.47zm.5-2.73c0 .308-.25.558-.558.558H17.03c2.339-1.323 4.173-4.414 4.817-8.412h.891c.035 0 .07-.002.104-.005v7.859zm.456-9.116c0 .308-.25.559-.559.559H1.262c-.308 0-.559-.25-.559-.559v-.895c0-.308.25-.558.559-.558h15.925c.194 0 .352-.157.352-.352 0-.194-.158-.351-.352-.351H4.39V2.36l1.166.583c.255.127.54.195.825.195h3.277c.285 0 .57-.068.825-.195l1.166-.583v3.103c0 .194.158.352.352.352.194 0 .352-.158.352-.352V2.36l1.166.583c.255.127.54.195.825.195h3.277c.285 0 .57-.068.825-.195l1.166-.583v3.783h-1.093c-.194 0-.351.157-.351.351 0 .195.157.352.351.352h4.22c.308 0 .559.25.559.558V8.3z" transform="translate(-262 -838) translate(135 710) translate(0 128) translate(127)"></path> </g> </g> </g> </g> </g> </svg>
                                        </div>
                                        <span>4 beds</span>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"> <g fill="none" fill-rule="evenodd"> <g fill="#1A1A1A" fill-rule="nonzero"> <g> <g> <g> <path d="M22.76 11.977H3.2V3.194C3.199 1.87 4.27.798 5.594.798h.798v1.637c-.93.19-1.597 1.008-1.597 1.956 0 .22.18.4.4.4h3.193c.22 0 .4-.18.4-.4 0-.948-.668-1.766-1.597-1.956V.4C7.19.18 7.012 0 6.79 0H5.595C3.83 0 2.4 1.43 2.4 3.194v8.783H1.202c-.661 0-1.197.536-1.197 1.197 0 .662.536 1.198 1.197 1.198.108 2.906 1.354 5.988 3.218 6.902-.603.644-.57 1.655.074 2.258.644.602 1.655.57 2.257-.075.484-.516.57-1.289.212-1.9h10.033c-.144.243-.221.518-.224.8-.005.881.705 1.6 1.587 1.606.882.005 1.601-.706 1.607-1.588.002-.408-.152-.802-.431-1.1 1.864-.915 3.11-3.997 3.225-6.903.662 0 1.198-.536 1.198-1.198 0-.661-.536-1.197-1.198-1.197zM7.19 3.26c.342.12.612.39.732.732h-2.26c.22-.624.904-.952 1.528-.732zm5.99 9.515h5.59v4.392h-5.59v-4.392zm-12.376.4c0-.221.179-.4.4-.4H12.38v.799H1.202c-.22 0-.399-.18-.399-.4zm4.79 9.98c-.44 0-.798-.358-.798-.799 0-.44.358-.798.799-.798.44 0 .798.357.798.798 0 .441-.357.799-.798.799zm12.776 0c-.441 0-.799-.358-.799-.799 0-.44.358-.798.799-.798.44 0 .798.357.798.798 0 .441-.357.799-.798.799zm0-2.396H5.594c-1.777 0-3.453-3.05-3.593-6.387h10.38v3.992c0 .22.178.4.399.4.22 0 .399-.18.399-.4v-.4h.798v.4c0 .22.18.4.4.4.22 0 .399-.18.399-.4v-.4h.798v.4c0 .22.18.4.4.4.22 0 .399-.18.399-.4v-.4h.798v.4c0 .22.179.4.4.4.22 0 .399-.18.399-.4v-.4h.798v.4c0 .22.179.4.4.4.22 0 .399-.18.399-.4v-3.992h2.395c-.14 3.337-1.817 6.387-3.593 6.387zm4.391-7.186h-3.193v-.798h3.193c.22 0 .4.179.4.4 0 .22-.18.398-.4.398z" transform="translate(-394 -838) translate(135 710) translate(0 128) translate(259)"></path> <path d="M7.19 5.988c0-.22-.178-.399-.398-.399-.22 0-.4.179-.4.4v.399c0 .22.179.399.4.399.22 0 .399-.179.399-.4v-.399zM7.19 7.984c0-.22-.178-.399-.398-.399-.22 0-.4.179-.4.4v.399c0 .22.179.399.4.399.22 0 .399-.179.399-.4v-.399zM6.792 9.581c-.221 0-.4.179-.4.4v.399c0 .22.179.399.4.399.22 0 .399-.179.399-.4V9.98c0-.22-.179-.399-.4-.399zM8.788 5.988c0-.22-.18-.399-.4-.399-.22 0-.399.179-.399.4v.399c0 .22.179.399.4.399.22 0 .399-.179.399-.4v-.399zM8.788 7.984c0-.22-.18-.399-.4-.399-.22 0-.399.179-.399.4v.399c0 .22.179.399.4.399.22 0 .399-.179.399-.4v-.399zM8.388 9.581c-.22 0-.399.179-.399.4v.399c0 .22.179.399.4.399.22 0 .399-.179.399-.4V9.98c0-.22-.18-.399-.4-.399zM5.594 5.988c0-.22-.179-.399-.4-.399-.22 0-.399.179-.399.4v.399c0 .22.18.399.4.399.22 0 .399-.179.399-.4v-.399zM5.594 7.984c0-.22-.179-.399-.4-.399-.22 0-.399.179-.399.4v.399c0 .22.18.399.4.399.22 0 .399-.179.399-.4v-.399zM5.195 9.581c-.22 0-.4.179-.4.4v.399c0 .22.18.399.4.399.22 0 .399-.179.399-.4V9.98c0-.22-.179-.399-.4-.399z" transform="translate(-394 -838) translate(135 710) translate(0 128) translate(259)"></path> </g> </g> </g> </g> </g> </svg>
                                        </div>
                                        <span>2 bathrooms</span>
                                    </li>
                                </ul>
                            
                            ';
                            
                            $veiwDetailHtml = '';
                            $roomDetailSection = '';


                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                    <div class="listroBox listroBox2 ">
                                        <figure class="mySwiper">
                                            <?php echo $faceHtml ?>
                                            <?php
                                                $imgHtml = '';
                                                foreach($imgArr as $imgList){
                                                    $img = WS_FRONT_SITE_IMG.'/room/'.$imgList;
                                                    $imgHtml .= "<a class='swiper-slide' href='$url'><div class='item'><img src='$img' class='img-fluid' alt='$rheader Image'></div></a>";
                                                
                                                }
                                            
                                                echo "
                                                        <div class='swiper-wrapper'>
                                                            $imgHtml
                                                        </div>
                                                        <div class='swiper-pagination'></div>
                                                    ";
                                                
                                            
                                            
                                            ?>
                                            
                                        </figure>
                                        <div class="listroBoxmain listroBoxmain2 imgCarousel">
                                            <div class="caption">
                                                <?php
                                                
                                                    echo $headerHtml.$roomDetailSection. $priceHtml 
                                                
                                                ?>
                                                
                                            </div>
                                        </div>
                                        <div class="btn_group">
                                            <?php
                                            
                                                if($room_rows['status'] == 1){
                                                    
                                                    if($price >= $advancePayPrice){
                                                        echo  "
                                                        
                                                            <div class='flex'>
                                                                <a class='btn btn-outline-secondary' target='_blank' style='text-align:center' href='https://jamindarspalace.com/contact.php'>Contact Us</a>
                                                                <a class='btn btn-info' style='text-align:center' target='_blank' href='https://jamindars.retrox.in/quick-pay.php'>Pay Advance</a>
                                                            </div>
                                                        
                                                        ";
                                                    }else{
                                                        echo  "
                                                        
                                                        <a class='button bg-gradient-info' style='text-align:center' href='$url'>Select Room</a>
                                                    
                                                    ";
                                                    }
                                                    
                                                }else{
                                                    echo  "
                                                        
                                                        <a class='button bg-gradient-danger' style='text-align:center' href='javascript:void(0)'>Not Available</a>
                                                    
                                                    ";
                                                }
                                            
                                            ?>
                                            
                                        </div>
                                    </div>
                                </div>

                                <?php
                                                    
                                    }
                                        }
                                    
                                    ?>


            </div>
        </div>
    </section>
    
    
    <?php if(count(getPackageArr()) > 0){ ?>

    <section id="packageSection" style="margin-top: 1%;">
        <div class="container">
            <div class="box">
                <div class="left">
                    <h2>Popular Package</h2>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis velit ratione, quas nisi dolorum exercitationem mollitia illum minima omnis numquam eveniet corporis doloribus inventore autem.</p>
                </div> 
                <div class="owl-carousel">
                    <?php
                    
                    foreach(getPackageArr() as $list){
                        $img = FRONT_SITE_IMG.'package/'.$list['img'];
                        $pid = $list['id'];
                        $slug = $list['slug'];
                        $name = $list['name'];
                        $desc = $list['description'];
                        $duration = $list['duration'];
                        $durationPrint = ($duration) .' Night '. ($duration + 1) .' Days' ;
                        $description = $desc;
                        $price = getPackagePriceById($pid,date('Y-m-d'));
                  
                        if($desc < 65){
                            $description = substr($desc,65).'...';
                        }
                        $url = FRONT_BOOKING_SITE.'/package.php?id='.$slug;
                        echo "
                        
                            <div class='content'>
                                <a target='_blank' href='$url'>
                                    <div class='img'>
                                        <img src='$img'>
                                        <div class='time'>$durationPrint</div>
                                    </div>
                                </a>
                                <div class='caption'>
                                    <a target='_blank' href='$url'><h4>$name</h4></a>
                                    <p>$description</p>
                                    <span class='price'>₹ $price</span>
                                </div>
                            </div>
                        
                        ";
                    }

                    ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php } ?>