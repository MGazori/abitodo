<?php defined('BASE_PATH') or die("Permision Denied"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= SITE_TITLE . "Login / Sign Up" ?></title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/img/favicon-64.png">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css'>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="<?= site_url('auth.php?action=register') ?>" method="POST">
                <h1>Create Account</h1>
                <div class="social-container" id="social-register">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registration</span>
                <span class="stay-login-msg">You'll stay loging in one this device for 20 days.</span>
                <input name="email" type="email" placeholder="Email" required />
                <input name="name" type="name" placeholder="Name" required />
                <input name="password" type="password" placeholder="Password" minlength="8" required />
                <button type="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="<?= site_url('auth.php?action=login') ?>" method="POST">
                <h1>Sign in</h1>
                <div class="social-container" id="social-login">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <span class="stay-login-msg">You'll stay loging in one this device for 20 days.</span>
                <input name="email" type="email" placeholder="Email" required />
                <input name="password" type="password" placeholder="Password" required />
                <a href="?action=reset-password" id="reset-password">Reset Password!</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <div class="project-info-container">
        <div class="project-info">
            <h4>This Open Source Project Developed In Beta Version By <a href="https://mgazori.com/">Mohammad Gazori</a>. Go To <a href="https://github.com/MGazori/abitodo">GitHub</a></h4>
        </div>
    </div>
    <script src="assets/js/auth.js"></script>
</body>

</html>