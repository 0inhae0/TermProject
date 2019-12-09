function Position(x, y) {
    this.x = x;
    this.y = y;
}

Position.prototype.isEqual = function (position) {
    return (position.x === this.x && position.y === this.y);
};