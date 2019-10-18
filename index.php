<?php
require_once "php/gridViewer.php";
require_once "php/htmlTransmitter.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Term Project</title>
    <link href="style/main.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <div class="heading">
            <h1 class="title">Term Project</h1>
        </div>
        <div class="above-game">
            <p>Play our sliding puzzle!</p>
            <div>
                <form method="post" name="sizeForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    Board size : <label>
                        <input name="sizeInput" type="number" min="3" max="10" value="<?php echo isset($_POST['sizeInput']) ? (string) $_POST['sizeInput'] : "4"; ?>">
                    </label>
                    <input type="submit" class="new-game-button" value="New Game">
                </form>
            </div>
        </div>

        <div class="game-container">
            <?php
            $htmlTransmitter = new htmlTransmitter();
            $htmlTransmitter->transmitGridContainer();
            ?>

            <?php
            $gridViewer = new gridViewer();
            $gridViewer->init();
            ?>
        </div>
    </div>
</body>
</html>
