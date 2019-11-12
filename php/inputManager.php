<?php
class inputManager {
    const UP = "UP";
    const RIGHT = "RIGHT";
    const DOWN = "DOWN";
    const LEFT = "LEFT";
    private $inputType;

    public function __construct($inputType) {
        $this->inputType = $inputType;
    }
}