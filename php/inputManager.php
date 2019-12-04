<?php
class inputManager {
    const UP = "UP";
    const RIGHT = "RIGHT";
    const DOWN = "DOWN";
    const LEFT = "LEFT";
    const KEYBOARD = 0;
    public $inputType;

    public function __construct($inputType = null) {
        if (isset($inputType)) $this->inputType = $inputType;
        else $this->inputType = self::KEYBOARD;
    }

    public function move(grid $grid, string $direction) {
        switch ($direction) {
            case self::UP:
                $moveX = -1;
                $moveY = 0;
                break;
            case self::RIGHT:
                $moveX = 0;
                $moveY = 1;
                break;
            case self::DOWN:
                $moveX = 1;
                $moveY = 0;
                break;
            case self::LEFT:
                $moveX = 0;
                $moveY = -1;
                break;
        }
        if (isset($moveX) && isset($moveY)) {
            $newX = $grid->nonePosition->getX() + $moveX;
            $newY = $grid->nonePosition->getY() + $moveY;
            if (($newX < $grid->size) && (0 <= $newX) && ($newY < $grid->size) && (0 <= $newY)) {
                $grid->moveTile(new position($newX, $newY));
            }
        }
    }
}