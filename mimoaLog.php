<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Mimoa Login Page</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link
        href="loginPageStyle.css"
        rel="stylesheet" type="text/css" />
    <script
        type="text/javascript">WebFont.load({ google: { families: ["Montserrat:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic", "Varela:400"] } });</script>
</head>

<body class="body">
    <section class="section"><img
            src="logo.jpg"
            loading="lazy" width="100" height="75" alt="" class="image" />
        <h2 class="heading-3">WELCOME TO MANG INASAL</h2>
    </section>
    <div id="quickstack" class="w-layout-layout quick-stack wf-layout-layout">
        <div id="quickstack" class="w-layout-cell cell">
            <div class="w-layout-hflex">
                <h1 class="heading">Mang</h1>
                <h1 class="heading-1">INASAL</h1>
            </div>
            <h3 class="heading-2">Ordering Website</h3>
            <div class="form-block w-form">
                <form id="loginForm" name="loginForm" action="mimoaVerify.php" method="POST" class="form" data-wf-page-id="65db244eb1abbdab07879b68" data-wf-element-id="cc7eac20-793d-ef42-e356-ba19e20bdcd8">
                        <label for="email-2" class="field-label-2">Email Address:</label>
                        <input class="text-field w-input" name="email" placeholder="Sample@gmail.com" type="email" id="email-2" required />
                        <label for="pass-2" class="field-label-3">Password:</label>
                        <input class="text-field-2 w-input" name="pass"  placeholder="******" type="password" id="pass-2" required />
                        <input type="submit" data-wait="Please wait..." id="subm" name="subm" class="submit-button w-button" value="Log In" />
                    </form>               
            </div>

            <div class="w-form">
                <form id="signInForm" action="mimoaRegis.php" method="POST" class="form-2" data-wf-page-id="65db244eb1abbdab07879b68" data-wf-element-id="443aa57a-d8c9-dfd4-3bb9-b9fce505aa93">
                    <label for="email-3" class="field-label-4">No account? Create Now!</label>
                    <input type="submit" name="submr" data-wait="Please wait..." class="submit-button-2 w-button" value="Create Account" />
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