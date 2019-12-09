function Grid(size) {
    this.size = size;
    this.tiles = this.addRandomTilesInit();
    if (!(this.isSolvable())) {
        for (x = 0; x < this.size; x++) {
            if (this.nonePosition.x !== x) {
                np = this.nonePosition;
                this.swapWithNoneTile(new Position(x, 0));
                this.swapWithNoneTile(new Position(x, 1));
                this.swapWithNoneTile(np);
                break;
            }
        }
    }
}

Grid.prototype.addRandomTilesInit = function () {
    var rtn = [];
    var inputValue = [];
    for (let i = 0; i < Math.pow(this.size, 2); i++) {
        inputValue.push(i);
    }
    shuffle(inputValue);
    for (let x = 0; x < this.size; x++) {
        rtn.push([]);
        for (let y = 0; y < this.size; y++) {
            rtn[x].push(inputValue[x * this.size + y]);
            if (rtn[x][y] === 0) {
                this.nonePosition = new Position(x, y);
            }
        }
    }

    return rtn;
};

Grid.prototype.swapWithNoneTile = function (position) {
    [this.tiles[this.nonePosition.x][this.nonePosition.y], this.tiles[position.x][position.y]] = [this.tiles[position.x][position.y], this.tiles[this.nonePosition.x][this.nonePosition.y]];
    this.nonePosition = position;
};

Grid.prototype.moveTile = function (position) {
    if (this.nonePosition.isEqual(position)) return;

    if (this.nonePosition.x === position.x) {
        while (this.nonePosition.y < position.y) {
            this.swapWithNoneTile(new Position(this.nonePosition.x, this.nonePosition.y + 1));
        }
        while (this.nonePosition.y > position.y) {
            this.swapWithNoneTile(new Position(this.nonePosition.x, this.nonePosition.y - 1));
        }
    }
    else if (this.nonePosition.y === position.y) {
        while (this.nonePosition.x < position.x) {
            this.swapWithNoneTile(new Position(this.nonePosition.x + 1, this.nonePosition.y));
        }
        while (this.nonePosition.x > position.x) {
            this.swapWithNoneTile(new Position(this.nonePosition.x - 1, this.nonePosition.y));
        }
    }
};

Grid.prototype.isSolvable = function () {
    var invCount = 0;
    for (i = 0; i < Math.pow(this.size, 2) - 1; i++) {
        let ixValue = Math.floor(i / this.size);
        let iyValue = i % this.size;
        let iTileValue = this.tiles[ixValue][iyValue];
        for (j = i + 1; j < Math.pow(this.size, 2); j++) {
            let jxValue = Math.floor(j / this.size);
            let jyValue = j % this.size;
            let jTileValue = this.tiles[jxValue][jyValue];
            if ((iTileValue > jTileValue) && (jTileValue !== 0)) {
                invCount++;
            }
        }
    }
    if (this.size % 2 === 1) {
        return (invCount % 2 === 0)
    }
    else {
        return ((invCount + this.size - this.nonePosition.x) % 2) === 1;
    }
};

function shuffle(a) {
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]];
    }
}