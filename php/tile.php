<?php
class tile {
    public $x;
    public $y;
    public $value;

    public function __construct($position, $value) {
        $this->x = $position['x'];
        $this->y = $position['y'];
        $this->value = $value;
    }

    public function isEqual(tile $to) {
        return ($to->x == $this->x && $to->y == $this->y && $to->value == $this->value);
    }
}