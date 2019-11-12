<?php
require_once "simpleRequire.php";
class tile {
    public $x;
    public $y;
    public $value;

    public function __construct(position $position, $value) {
        $this->x = $position->getX();
        $this->y = $position->getY();
        $this->value = $value;
    }

    public function isEqual(tile $to) {
        return ($to->x == $this->x && $to->y == $this->y && $to->value == $this->value);
    }
}