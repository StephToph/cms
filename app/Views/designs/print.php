<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Image</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <img id="printImage" src="">
    <script>
        // Get the image data from the query parameter
        var imageData = new URLSearchParams(window.location.search).get('img');

        // Set the image source
        document.getElementById('printImage').src = imageData;

        // Print the page automatically
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
