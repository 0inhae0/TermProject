<?php
require_once "php/grid.php";
require_once "php/keyboardInputManager.php";
require_once "php/tile.php";
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
            <div class="grid-container">
                <?php $size = isset($_POST["sizeInput"]) ? $_POST["sizeInput"] : 4;
                    $gridSize = $size;
                    $gridCellWidth = (500 - (20 * ($gridSize + 1))) / $gridSize;
                ?>
                <?php for ($i = 0; $i < $gridSize; $i++) {
                        ?>
                <div class="grid-row">
                    <?php for ($j = 0; $j < $gridSize; $j++) {
                        ?>
                    <div class="grid-cell" style="width: <?php echo $gridCellWidth; ?>px; height: <?php echo $gridCellWidth; ?>px;"></div>
                    <?php }
                    ?>
                </div>
                <?php }
                ?>
            </div>

            <div class="tile-container">
                <form method="post" name="tile-container" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <style> <?php require_once "style/main.css"; ?> </style>
                    <?php
                    require_once "php/tileViewer.php";
                    function start() {
                        if (isset($_POST["sizeInput"])) {
                            if (isset($_POST["previousGrid"])) {
                                for ($x = 0; $x < $_POST["sizeInput"]; $x++) {
                                    for ($y = 0; $y < $_POST["sizeInput"]; $y++) {
                                        if (isset($_POST[$x . "-" . $y])) {
                                            new tileViewer((int)$_POST["sizeInput"], $_POST["previousGrid"], ["x" => $x, "y" => $y]);
                                            return;
                                        }
                                    }
                                }
                            }
                            new tileViewer((int)$_POST["sizeInput"]);
                            return;
                        } else {
                            new tileViewer(4);
                            return;
                        }
                    }
                    start();
                    ?>
                </form>
          </div>
        </div>
    </div>
</body>
</html>
