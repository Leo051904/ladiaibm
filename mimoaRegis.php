<?php
    require "mimoaConx.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Mimoa Login Page</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link
        href="regisPageStyle.css"
        rel="stylesheet" type="text/css" />
    <script
        type="text/javascript">WebFont.load({ google: { families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic", "Varela:400"] } });</script>
</head>

<body class="body">
    <section class="section"><img
            src="logo.jpg"
            loading="lazy" width="100" height="75" alt="" class="image" />
        <h2 class="heading-3">REGISTRATION</h2>
    </section>
    <div id="quickstack" class="w-layout-layout quick-stack wf-layout-layout">
        <div id="quickstack" class="w-layout-cell cell">
            <div class="w-layout-hflex">
                <h1 class="heading">Mang</h1>
                <h1 class="heading-1">INASAL</h1>
            </div>
            <h3 class="heading-2">Ordering Website</h3>
            <div class="form-block w-form">
                <form id="loginForm" name="loginForm" action="mimoaRegisConx.php" method="POST" class="form">
                        <label for="fname" class="field-label-2">First Name:</label>
                        <input class="text-field w-input" name="fname" placeholder="First Name" type="text" id="fname" required />

                        <label for="lname" class="field-label-2">Last Name:</label>
                        <input class="text-field w-input" name="lname" placeholder="Last Name" type="text" id="lname" required />

                        <label for="email-2" class="field-label-2">Email Address:</label>
                        <input class="text-field w-input" name="email" placeholder="Sample@gmail.com" type="email" id="email-2" required />

                        <label for="pass-2" class="field-label-3">Password:</label>
                        <input class="text-field-2 w-input" name="pass"  placeholder="******" type="password" id="pass-2" required />

                        <label for="phone" class="field-label-2">Phone:</label>
                        <input class="text-field w-input" name="phone" placeholder="09xxxxxxxxx" type="text" id="phone" required />

                        <input type="submit" data-wait="Please wait..." id="subm" name="subm" class="submit-button w-button" value="REGISTER" />
                    </form>               
            </div>

            <div class="w-form">
                <form id="signInForm" action="mimoaLog.php" method="POST" class="form-2">
                    <input type="submit" name="back" data-wait="Please wait..." class="submit-button-2 w-button" value="Back" />
                </form>            
            </div>

        </div>
        <div id="slideshow" class="w-layout-cell cell-2"><img
                sizes="(max-width: 767px) 100vw, 800px"
                srcset="slideshow2.png 500w, slideshow2.png 700w"               
                src="slideshow2.png"
                loading="eager" class="image-3" /></div>
        <div id="slideshow" class="w-layout-cell"></div>
    </div>
</body>

</html>