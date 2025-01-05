<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo site_url('assets/favicon.png'); ?>">
    <title></title>
    <style>
         /* Global styles for light theme */
         body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            font-size: 24px;
            color: #0056b3;
            margin-bottom: 20px;
        }
        .message {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 15px 25px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        /* Dark theme styles */
        @media (prefers-color-scheme: dark) {
            body {
                color: #fff;
                background-color: #1c1c1c;
            }
            .container {
                background-color: #333;
            }
            .header {
                color: #4db8ff;
            }
            .message {
                background-color: #2e2e2e;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
            }
            .btn {
                background-color: #4db8ff;
            }
            .footer {
                color: #aaa;
            }
        }
    </style>
</head>

<body class="body">
    <div class="mail_wrapper">
        <div class="top">
            <img alt="logo" src="<?php echo site_url('assets/new_logo1.png'); ?>" height="70"/>
        </div>

        <div class="main"><?php echo ($body); ?></div>

    </div>
    <div class="footer">
        <p>&copy; 2025 The Regional Camp Meeting. All rights reserved.</p>
    </div>
</body>
</html>