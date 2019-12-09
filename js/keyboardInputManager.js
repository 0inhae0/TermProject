function KeyboardInputManager() {
    this.events = {};
    this.listen();
}

KeyboardInputManager.prototype.on = function (event, callback) {
    if (!this.events[event]) {
        this.events[event] = [];
    }
    this.events[event].push(callback);
};

KeyboardInputManager.prototype.listen = function () {
    var map = {
        "ArrowUp": 0, // Up
        "ArrowRight": 1, // Right
        "ArrowDown": 2, // Down
        "ArrowLeft": 3, // Left
        "KeyW": 0, // W
        "KeyD": 1, // D
        "KeyS": 2, // S
        "KeyA": 3, // A
        "Numpad8": 0, // Number pad 8
        "Numpad6": 1, // Number pad 6
        "Numpad2": 2, // Number pad 4
        "Numpad4": 3, // Number pad 2
    };

    var self = this;
    document.addEventListener("keydown", function (event) {
        var mapped = map[event.code];
        if (mapped !== undefined) {
            self.emit("move", mapped);
        }
    });

    this.bindButtonPress(".new-game-button", this.restart);
    this.bindButtonPress(".auto-button", this.auto);
};

KeyboardInputManager.prototype.emit = function (event, data) {
    var callbacks = this.events[event];
    if (callbacks) {
        callbacks.forEach(function (callback) {
            callback(data);
        });
    }
};

KeyboardInputManager.prototype.restart = function (event) {
    this.emit("restart");
};

KeyboardInputManager.prototype.auto = function (event) {
    this.emit("auto");
};

KeyboardInputManager.prototype.bindButtonPress = function (selector, doThis) {
    var button = document.querySelector(selector);
    button.addEventListener("click", doThis.bind(this));
};