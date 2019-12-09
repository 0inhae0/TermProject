<?php
require_once "php/simpleRequire.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Term Project</title>
    <link href="style/main.css" rel="stylesheet" type="text/css">
</head>
<body onload="document.sizeForm.submit()">
    <header>
        <h1 class="title">Term Project</h1>
        <h3 class="description">Play our sliding puzzle!</h3>
    </header>
    <main>
        <form method="post" target="game-container" id="sizeForm" name="sizeForm" class="new-game-form" action="php/gameContainer_html.php">
            <label for="sizeInput">Board size : </label>
            <input type="number" id="sizeInput" name="sizeInput" min="3" max="10" value="<?php echo isset($_POST['sizeInput']) ? (string) $_POST['sizeInput'] : "4"; ?>">
            <input type="submit" id="New_Game" name="New_Game" class="new-game-button" value="New Game">
            <a href="jsversion.php">To JS Version</a>
        </form>
        <iframe class="game-container" id="game-container" name="game-container">iframe 미지원 브라우저입니다.</iframe>
    </main>
    <footer>
        <h4>인터넷 프로그래밍 4조</h4>
    </footer>
</body>
</html>
