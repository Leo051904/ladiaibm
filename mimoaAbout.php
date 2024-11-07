<?php 
  session_start();

  require "mimoaConx.php";

  if(isset($_POST['uID'])) {
      $uID = $_POST['uID'];
  }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Mimoa Login Page</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link
        href="aboutPageStyle.css"
        rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script
        type="text/javascript">WebFont.load({ google: { families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic", "Varela:400", "Changa One:400,400italic", "Exo:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic", "Varela Round:400", "PT Sans:400,400italic,700,700italic", "Bitter:400,700,400italic"] } });</script>

</head>

<body class="body">
    <div class="w-layout-blockcontainer container-2 w-container">
        <div class="divblockheader"><img
                src="logo.jpg"
                loading="lazy" width="90" height="80" alt="" class="image-5" />
            <h1 class="heading-9">ABOUT US</h1>
        </div>
        <div class="divblock1">
            <h3 class="heading-7">Welcome to Mang Inasal Ordering Website! We&#x27;re thrilled to be part of your dining
                experience. Our mission is to serve you delicious meals with convenience and excellence.</h3>
        </div>
        <div class="divblock2">
            <h2><strong class="bold-text">What We Offer</strong></h2>
        </div>
        <div class="divblock3">
            <ul role="list" class="list">
                <li>Authentic Cuisine: Indulge in our carefully crafted dishes made with the freshest ingredients and
                    traditional recipes.</li>
                <li>Convenience: Order your favorite meals with ease through our mobile app, whether you&#x27;re at home
                    or on the go.</li>
                <li>Quality Service: Our team is dedicated to providing you with friendly service and ensuring your
                    satisfaction with every order.</li>
                <li>Community: We&#x27;re proud to be a part of your neighborhood and strive to create a welcoming
                    atmosphere for all.</li>
            </ul>
        </div>
        <div class="divblock4">
            <h2><strong class="bold-text-2">Our Team</strong></h2>
        </div>
        <div class="divblock5">
            <h3 class="heading-8">At Mang Inasal, our chefs, servers, and staff are passionate about delivering
                exceptional dining experiences. We&#x27;re here to make sure every meal is memorable.</h3>
        </div>
        <div class="divblockbtn">
            <div class="w-form">
                <form id="email-form" name="email-form" data-name="Email Form" action="mimoaHomePage.php" method="post">
                    <input type="hidden" id="uID" name="uID" value="<?php echo $uID; ?>">
                    <input type="submit" name="back" data-wait="Please wait..." id="returnBtn" class="submit-button-7 w-button" value="Return" />
                </form>
            </div>
        </div>
    </div>
</body>

</html>