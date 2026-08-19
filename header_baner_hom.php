<!--<div style="background-color:none   ; color:white margin:1px; padding:1px; border:0px solid blue;">
<meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">

<img  id="" data-rel="colorbox" class="rectangle" width="1350" height="90"  src="logo/bb.png" />
</div>-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Header</title>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Responsive header container */
        .header-container {
            background-color: none; /* Transparent background */
            margin: 5px; /* Slight margin for spacing */
            padding: 5px; /* Padding around content */
            display: flex;
            justify-content: center; /* Center the content horizontally */
            align-items: center; /* Center the content vertically */
            border: 0px solid blue; /* Optional border for testing */
        }

        /* Responsive image */
        .header-container img {
            width: 100%; /* Ensures the image scales with the container */
            max-width: 1920px; /* Restricts the image from becoming too large */
            height: auto; /* Maintains the aspect ratio of the image */
        }
    </style>
</head>
<body>
    <div class="header-container">
        <img id="logo" class="rectangle" src="logo/b1.png" alt="Logo">
    </div>
</body>
</html>
