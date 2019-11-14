<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-type" content="text/html">
    <link href="../style/main.css" type="text/css" rel="stylesheet">
    <title>Game Container</title>
</head>
<body>
<?php
require_once "simpleRequire.php";

$gridViewer = new gridViewer();
$gridViewer->htmlTransmitter->transmitGridContainer();
$gridViewer->init();
$gridViewer->htmlTransmitter->transmitMoveDirection($gridViewer->grid);
?>
</body>
</html>
