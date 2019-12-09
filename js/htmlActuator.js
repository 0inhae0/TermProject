function HTMLActuator() {
    this.tileContainer = document.querySelector(".tile-container");
    this.gridContainer = document.querySelector(".grid-container");
    this.colorPicker = ["rgb(255,191,228)", "rgb(253, 254, 2)", "rgb(11, 255, 1)", "rgb(12, 125, 254)", "rgb(254, 0, 246)", "rgb(250, 123, 67)", "rgb(140, 140, 246)", "rgb(163, 241, 160)", "rgb(254, 0, 0)"]
}

HTMLActuator.prototype.actuate = function (grid) {
    var self = this;
    window.requestAnimationFrame(function () {
        self.clearContainer(self.tileContainer);
        self.clearContainer(self.gridContainer);
        for (x = 0; x < grid.size; x++) {
            var gridRow = document.createElement("div");
            self.applyClasses(gridRow, ["grid-row"]);
            for (y = 0; y< grid.size; y++) {
                var gridCell = document.createElement("div");
                self.applyClasses(gridCell, ["grid-cell"]);
                gridCell.setAttribute("style", "width: " + self.getWidth(grid.size) + "px; height: " + self.getWidth(grid.size) + "px");
                gridRow.appendChild(gridCell);
                self.addTile(x, y, grid.tiles[x][y], grid);
            }
            self.gridContainer.appendChild(gridRow);
        }
    });
};

HTMLActuator.prototype.addTile = function (x, y, value, grid) {
    var wrapper = document.createElement("div");
    var inner = document.createElement("div");
    var positionClass = this.positionClass(new Position(x, y));

    var classes = ["tile", "tile-" + value, positionClass];
    this.applyClasses(wrapper, classes);

    inner.classList.add("tile-inner");
    inner.textContent = value !== 0 ? value : null;
    inner.setAttribute("style",
        "width: " + this.getWidth(grid.size) + "px;" +
        "height: " + this.getWidth(grid.size) + "px;" +
        "font-size: " + this.getWidth(grid.size) / 2 + "px;" +
        "line-height: " + this.getWidth(grid.size) + "px;");
    wrapper.appendChild(inner);

    var min = Math.floor((value - 1) / grid.size) < ((value - 1) % grid.size) ? Math.floor((value - 1) / grid.size) : ((value - 1) % grid.size);
    wrapper.setAttribute("style",
        "width: " + this.getWidth(grid.size) + "px;" +
        "height: " + this.getWidth(grid.size) + "px;" +
        "background-color: " + this.colorPicker[min]);
    this.tileContainer.appendChild(wrapper);
};

HTMLActuator.prototype.applyClasses = function (element, classes) {
    element.setAttribute("class", classes.join(" "));
};

HTMLActuator.prototype.positionClass = function (position) {
    return "tile-position-" + position.x + "-" + position.y;
};

HTMLActuator.prototype.getWidth = function (size) {
    // 전체 보드판의 크기가 500px
    // tile 사이의 간격이 20px
    return ((500 - (20 * (size + 1))) / size);
};

HTMLActuator.prototype.clearContainer = function (container) {
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }
};
