<?php
require_once "simpleRequire.php";
class tileMover {
    public $grid;

    public function __construct(grid $grid) {
        $this->grid = $grid;
    }

    public function move(string $direction) {
        switch ($direction) {
            case inputManager::UP:
                $moveX = -1;
                $moveY = 0;
                break;
            case inputManager::RIGHT:
                $moveX = 0;
                $moveY = 1;
                break;
            case inputManager::DOWN:
                $moveX = 1;
                $moveY = 0;
                break;
            case inputManager::LEFT:
                $moveX = 0;
                $moveY = -1;
                break;
        }

        if (isset($moveX) && isset($moveY)) {
            $this->grid->moveTile(new position($this->grid->nonePosition->getX() + $moveX, $this->grid->nonePosition->getY() + $moveY));
        }
    }
}
