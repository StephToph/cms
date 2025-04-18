<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo site_url('assets/favicon.png'); ?>">
    <title></title>
    <style>
        .body { width: 100%; }
        .mail_wrapper { border: 1px solid #eee; margin: 10px 5px; border-radius: 10px; }
        .mail_wrapper .top { border-radius: 10px 10px 0px 0px; padding: 15px; background-color: #fcfcfc; text-align: center; }
        .mail_wrapper .top img { max-width: 80%; }
        .mail_wrapper .main { padding: 15px;  }
        .mail_wrapper .bottom { border-radius: 0px 0px 10px 10px; padding: 15px; background-color: #fcfcfc; text-align: center; font-size: small; }
    </style>
</head>

<body class="body">
    <div class="mail_wrapper">
        <div class="top">
            <img alt="logo" src="<?php echo site_url('assets/new_logo1.png'); ?>" height="70"/>
        </div>

        <div class="main"><?php echo ($body); ?></div>

        <!-- <div class="bottom">
            &copy; <?php echo date('Y'); ?> - <?php echo app_name; ?><br>
            <strong>Contact Us:</strong><br>
            Email: <a href="mailto:hello@loveworldbuddy.com">hello@loveworldbuddy.com</a><br>
            Phone: <a href="tel:+12405430620" style="text-decoration: none; color: #000;">+1 240 543 0620</a>
           
        </div> -->
    </div>
</body>
</html>