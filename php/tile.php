<?php
class tile {
    public $x;
    public $y;
    public $value;
    public $previousPosition;

    public function __construct($position, $value) {
        $this->x = $position["x"];
        $this->y = $position["y"];
        $this->value = $value;

        $this->previousPosition = null;
    }

    public function savePosition() {
        $this->previousPosition = ["x"=> $this->x, "y"=> $this->y];
    }

    public function updatePosition($position) {
        $this->x = $position["x"];
        $this->y = $position["y"];
    }

    public function serialize() {
        return ["position" => ["x" => $this->x, "y" => $this->y], "value" => $this->value];
    }
}