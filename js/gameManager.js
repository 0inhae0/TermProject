function GameManager(size, InputManager, Actuator) {
    this.size = size;
    this.inputManager = new InputManager;
    this.htmlActuator = new Actuator;
    this.count = 0;

    this.inputManager.on("move", this.move.bind(this));
    this.inputManager.on("restart", this.restart.bind(this));
    this.inputManager.on("auto", this.auto.bind(this));

    this.setup();
}

GameManager.prototype.setup = function () {
    this.grid = new Grid(this.size);
    this.actuate();
};

GameManager.prototype.actuate = function () {
    var self = this;
    window.requestAnimationFrame(function () {
        document.getElementById("count-label").innerHTML = "NO OF MOVE : " + self.count++;
    });
    this.htmlActuator.actuate(this.grid);
};

GameManager.prototype.move = function (direction) {
    // 0: up, 1: right, 2: down, 3: left

    var vector = this.getVector(direction);
    var nonePosition = this.grid.nonePosition;
    var moved = false;
    var newX = nonePosition.x + vector.x;
    var newY = nonePosition.y + vector.y;

    if ((newX < this.grid.size) && (0 <= newX) && (newY < this.grid.size) && (0 <= newY)) {
        this.grid.moveTile(new Position(newX, newY));
        moved = true;
    }

    if (moved) {
        this.actuate();
    }
};

GameManager.prototype.restart = function () {
    this.sizeInput = document.getElementById("sizeInput");
    this.size = Number(this.sizeInput.value);
    this.grid = new Grid(this.size);
    this.count = 0;
    this.actuate();
};

GameManager.prototype.getVector = function (direction) {
    var map = {
        0: {x: -1, y: 0}, // Up
        1: {x: 0, y: 1}, // Right
        2: {x: 1, y: 0}, // Down
        3: {x: 0, y: -1}, // Left
    };

    return map[direction];
};

GameManager.prototype.auto = function () {
    this.count = 0;
    getRequest("/TermProject/php/myGridSolver.php", "grid=" + JSON.stringify(this.grid.tiles), requestSuccess, requestFail, this);
    this.actuate();
};

async function requestSuccess(responseText, gameManager) {
    console.log("Request Success");
    console.log(responseText);
    for (let i = 0; i < responseText.length; i++) {
        switch (responseText.charAt(i)) {
            case 'U':
                gameManager.move(0);
                break;
            case 'R':
                gameManager.move(1);
                break;
            case 'D':
                gameManager.move(2);
                break;
            case 'L':
                gameManager.move(3);
                break;
        }
        await new Promise(resolve => setTimeout(resolve, 1));
    }
}

function requestFail() {
    console.log("Request Failed");
}

function getRequest(url, content, success, error, caller) {
    var req = false;
    try{
        // most browsers
        req = new XMLHttpRequest();
    } catch (e){
        // IE
        try{
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            // try an older version
            try{
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                return false;
            }
        }
    }
    if (!req) return false;
    if (typeof success != 'function') success = function () {};
    if (typeof error != 'function') error = function () {};
    req.onreadystatechange = function(){
        if(req .readyState === 4){
            return req.status === 200 ? success(req.responseText, caller) : error(req.status);
        }
    };
    console.log("START");
    req.open("GET", url + "?" + content, true);
    req.send(null);
    console.log("END");
    return req;
}