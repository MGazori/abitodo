<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/img/favicon-64.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/reset-pass.css">
</head>

<body>
    <div class="container" id="container">
        <form action="<?= site_url('process/ajaxHandler.php') ?>" method="POST" id="reset-mail">
            <h1 id="reset-pass-title">Reset Password</h1>
            <span class="forget-pass-msg">We send reset code to your email</span>
            <label id="reset-label">Enter yore email address:</label>
            <input id="user-email" name="email" type="email" placeholder="Email" required="">
            <button id="reset-pass-button" type="submit">Send Mail</button>
            <a id="go-to-auth" href="auth.php"> Sign in / Sign up</a>
        </form>
    </div>
    <div class="project-info-container">
        <div class="project-info">
            <h4>This Open Source Project Developed In Beta Version By <a href="https://mgazori.com/">Mohammad Gazori</a>. Go To <a href="https://github.com/MGazori/abitodo">GitHub</a></h4>
        </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src="assets/js/reset-pass.js"></script>
</body>

</html>