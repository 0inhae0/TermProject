<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="style/VerIH.css" rel="stylesheet" type="text/css">
    <title>TermProject with JS</title>
</head>
<body>
    <header>
        <h1 class="title">SLIDING<br>PUZZLE</h1>
        <h3 class="description">Play our sliding puzzle!</h3>
    </header>
    <main>
        <div class="game-container">
            <div class="new-game-form">
                <label for="sizeInput" class="yellow-label" id="size-label">BOARD SIZE : 4</label>
                <input class="slider" type="range" id="sizeInput" min="3" max="10" value="4" oninput="document.getElementById('size-label').innerHTML = 'BOARD SIZE : ' + this.value">
                <br>
                <button class="new-game-button">New Game</button>
                <a class="auto-button">Auto Complete</a>
            </div>
            <label class="yellow-label" id="count-label">NO OF MOVE : 0</label>
            <div class="grid-container">

            </div>
            <div class="tile-container">

            </div>
        </div>
    </main>
    <footer>
        <h4>인터넷 프로그래밍 4조</h4>
    </footer>

    <script type="text/javascript" src="js/grid.js"></script>
    <script type="text/javascript" src="js/keyboardInputManager.js"></script>
    <script type="text/javascript" src="js/htmlActuator.js"></script>
    <script type="text/javascript" src="js/gameManager.js"></script>
    <script type="text/javascript" src="js/position.js"></script>
    <script type="text/javascript" src="js/application.js"></script>
</body>
</html>