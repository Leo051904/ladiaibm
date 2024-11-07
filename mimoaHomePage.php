<?php
    require "mimoaConx.php";
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: mimoaLog.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM user WHERE USER_ID = '$user_id'";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>homepage</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta/>
    <link
        href="homePageStyle.css"
        rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script
        type="text/javascript">WebFont.load({ google: { families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic", "Varela:400", "Changa One:400,400italic", "Exo:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic"] } });</script>
</head>

<body>
    <section class="section-3">
        <div class="div-block"><img
                src="logo.jpg"
                loading="lazy" width="Auto" height="65" alt="" />
            <h4 class="heading-6">HOMEPAGE</h4>
            <h4 class="heading-4">The best-tasting 2-in-1 sa laki, tagos ang Ihaw-Sarap Chicken Inasal!</h4>
        </div>
        <div class="div-block-2">
            <div class="w-form">
                <form id="accAbtBtn" name="wf-form-accBtn" data-name="accBtn" action="mimoaAcc.php" method="post" class="form-6">
                    <input type="hidden" id="uID" name="uID" value="<?php echo $uID; ?>">
                    <input type="submit" data-wait="Please wait..." class="submit-button-4 w-button" value="Account" />
                </form>              
            </div>
            <div class="w-form">
                <form id="accAbtBtn" name="wf-form-accBtn" data-name="abtBtn" action="mimoaAbout.php" method="post" class="form-6">
                    <input type="hidden" id="uID" name="uID" value="<?php echo $uID; ?>">
                    <input type="submit" data-wait="Please wait..." class="submit-button-3 w-button" value="About" />
                </form>       
            </div>
        </div>
    </section>
    <div class="container">
        <h1 class="heading-5">Magandang Araw, <?php echo  $row["USER_FNAME"] . " " . $row["USER_LNAME"]; ?></h1>
        <div class="w-form">
            <form id="wf-form-orderBtn" name="wf-form-orderBtn" data-name="orderBtn" action="mimoaOrder.php" method="post" class="form-3">
                <input type="hidden" id="uID" name="uID" value="<?php echo $uID; ?>">
                <label for="name" class="field-label-5">Order Now!</label>
                <input type="submit" name="order" data-wait="Please wait..." class="submit-button-5 w-button" value="&gt;&gt; ORDER &lt;&lt;" />
            </form>           
        </div>
    </div>
    <div id="quickstack"
        class="w-layout-layout quick-stack-2 wf-layout-layout">
        <div id="divQuickStack" class="w-layout-cell cell-3">
            <div data-delay="4000" data-animation="slide" class="slider-2 w-slider" data-autoplay="true"
                data-easing="ease" data-hide-arrows="false" data-disable-swipe="false" data-autoplay-limit="0"
                data-nav-spacing="3" data-duration="300" data-infinite="true">
                <div class="w-slider-mask">
                    <div class="slide w-slide"></div>
                    <div class="slide-2 w-slide"></div>
                </div>    
            </div>
        </div>
    </div>
    <section class="section-4">
        <div class="w-form">
            <form id="wf-form-logoutBtn" name="wf-form-logoutBtn" data-name="logoutBtn" action="mimoaLogout.php" method="post" class="form-5">
                <input type="hidden" id="uID" name="uID" value="<?php echo $uID; ?>">
                <input type="submit" data-wait="Please wait..." class="submit-button-6 w-button" value="Log Out" />
            </form>
        </div>
    </section>
    <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=65db244eb1abbdab07879b5d"
        type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
    <script src="https://assets-global.website-files.com/65db244eb1abbdab07879b5d/js/webflow.92f4b9c61.js"
        type="text/javascript"></script>
</body>

</html>

