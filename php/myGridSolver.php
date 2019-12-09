<?php
require_once "simpleRequire.php";

class myGridSolver {
    /**
     * @var int
     */
    public $num;
    /**
     * @var grid
     */
    public $grid;
    /**
     * @var position
     */
    public $numberPosition;
    /**
     * @var position
     */
    public $emptyPosition;

    public function __construct($grid) {
        if (gettype($grid) == "object") {
            $this->grid = $grid;
        }
        else if (gettype($grid) == "string") {
            $tiles = json_decode($grid);
            $size = count($tiles);
            $this->grid = new grid($size);
            $this->grid->tiles = $tiles;
            $this->grid->nonePosition = $this->grid->getPosition(0);
            $this->responseBody = "";
        }
        $this->numberPosition = new position();
        $this->emptyPosition = new position();
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
            $newX = $this->grid->nonePosition->getX() + $moveX;
            $newY = $this->grid->nonePosition->getY() + $moveY;
            if (($newX < $this->grid->size) && (0 <= $newX) && ($newY < $this->grid->size) && (0 <= $newY)) {
                $this->grid->moveTile(new position($newX, $newY));
                if (isset($this->responseBody)) $this->responseBody .= $direction[0];
            }
        }
        $this->numberPosition = $this->grid->getPosition($this->num);
    }

    public function is_correct(grid $grid) {
        $num = 1;
        for ($i = 0; $i < $grid->size; $i++) {
            for ($j = 0; $j < $grid->size; $j++) {
                if ($grid->tiles[$i][$j] != $num && !($i == $grid->size - 1 && $j == $grid->size - 1))
                    return false;
                $num++;
            }
        }
        return true;
    }

    public function myMove($one, $twoWillBeDoubled, $three, $four) {
        $this->move($one);
        $this->move($twoWillBeDoubled);
        $this->move($twoWillBeDoubled);
        $this->move($three);
        $this->move($four);
    }

    public function mySolveUpperLine($number, $toPosition) {
        while ($this->grid->nonePosition->y < $toPosition->y) {
            $this->move(inputManager::RIGHT);
        }
        while ($this->grid->nonePosition->y > $toPosition->y) {
            $this->move(inputManager::LEFT);
        }

        $numberPosition = $this->grid->getPosition($number);
        while ($this->grid->nonePosition->x > $numberPosition->x) {
            $this->move(inputManager::UP);
            $numberPosition = $this->grid->getPosition($number);
        }
        while ($this->grid->nonePosition->x < $numberPosition->x) {
            $this->move(inputManager::DOWN);
            $numberPosition = $this->grid->getPosition($number);
        }
        if ($numberPosition->y != $toPosition->y) {
            if ($numberPosition->y > $toPosition->y) {
                while ($this->grid->nonePosition->y < $numberPosition->y) {
                    $this->move(inputManager::RIGHT);
                    $numberPosition = $this->grid->getPosition($number);
                }
                if ($numberPosition->x == $this->grid->size - 1) {
                    while ($numberPosition->y != $toPosition->y) {
                        $this->myMove(inputManager::UP, inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::UP);
                    $this->move(inputManager::LEFT);
                    $this->move(inputManager::DOWN);
                } else {
                    while ($numberPosition->y != $toPosition->y) {
                        $this->myMove(inputManager::DOWN, inputManager::LEFT, inputManager::UP, inputManager::RIGHT);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::DOWN);
                    $this->move(inputManager::LEFT);
                }
            } else {
                while ($this->grid->nonePosition->y > $numberPosition->y) {
                    $this->move(inputManager::LEFT);
                    $numberPosition = $this->grid->getPosition($number);
                }
                if ($numberPosition->x == $this->grid->size - 1) {
                    while ($numberPosition->y != $toPosition->y) {
                        $this->myMove(inputManager::UP, inputManager::RIGHT, inputManager::DOWN, inputManager::LEFT);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::UP);
                    $this->move(inputManager::RIGHT);
                    $this->move(inputManager::DOWN);
                } else {
                    while ($numberPosition->y != $toPosition->y) {
                        $this->myMove(inputManager::DOWN, inputManager::RIGHT, inputManager::UP, inputManager::LEFT);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::DOWN);
                    $this->move(inputManager::RIGHT);
                }
            }
        }
        $numberPosition = $this->grid->getPosition($number);

        if ($toPosition->y == $this->grid->size - 1) {
            while ($numberPosition->x > $toPosition->x) {
                $this->myMove(inputManager::LEFT, inputManager::UP, inputManager::RIGHT, inputManager::DOWN);
                $numberPosition = $this->grid->getPosition($number);
            }
        } else {
            while ($numberPosition->x > $toPosition->x) {
                $this->myMove(inputManager::RIGHT, inputManager::UP, inputManager::LEFT, inputManager::DOWN);
                $numberPosition = $this->grid->getPosition($number);
            }
        }
    }

    public function mySolveLefterLine($number, $toPosition) {
        while ($this->grid->nonePosition->x < $toPosition->x) {
            $this->move(inputManager::DOWN);
        }
        while ($this->grid->nonePosition->x > $toPosition->x) {
            $this->move(inputManager::UP);
        }

        $numberPosition = $this->grid->getPosition($number);
        while ($this->grid->nonePosition->y > $numberPosition->y) {
            $this->move(inputManager::LEFT);
            $numberPosition = $this->grid->getPosition($number);
        }
        while ($this->grid->nonePosition->y < $numberPosition->y) {
            $this->move(inputManager::RIGHT);
            $numberPosition = $this->grid->getPosition($number);
        }

        if ($numberPosition->x != $toPosition->x) {
            if ($numberPosition->x > $toPosition->x) {
                while ($this->grid->nonePosition->x < $numberPosition->x) {
                    $this->move(inputManager::DOWN);
                    $numberPosition = $this->grid->getPosition($number);
                }
                if ($numberPosition->y == $this->grid->size - 1) {
                    while ($numberPosition->x != $toPosition->x) {
                        $this->myMove(inputManager::LEFT, inputManager::UP, inputManager::RIGHT, inputManager::DOWN);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::LEFT);
                    $this->move(inputManager::UP);
                    $this->move(inputManager::RIGHT);
                } else {
                    while ($numberPosition->x != $toPosition->x) {
                        $this->myMove(inputManager::RIGHT, inputManager::UP, inputManager::LEFT, inputManager::DOWN);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::RIGHT);
                    $this->move(inputManager::UP);
                }
            } else {
                while ($this->grid->nonePosition->x > $numberPosition->x) {
                    $this->move(inputManager::UP);
                    $numberPosition = $this->grid->getPosition($number);
                }
                if ($numberPosition->y == $this->grid->size - 1) {
                    while ($numberPosition->x != $toPosition->x) {
                        $this->myMove(inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT, inputManager::UP);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::LEFT);
                    $this->move(inputManager::DOWN);
                    $this->move(inputManager::RIGHT);
                } else {
                    while ($numberPosition->x != $toPosition->x) {
                        $this->myMove(inputManager::RIGHT, inputManager::DOWN, inputManager::LEFT, inputManager::UP);
                        $numberPosition = $this->grid->getPosition($number);
                    }
                    $this->move(inputManager::RIGHT);
                    $this->move(inputManager::DOWN);
                }
            }
        }
        $numberPosition = $this->grid->getPosition($number);

        if ($toPosition->x == $this->grid->size - 1) {
            while ($numberPosition->y > $toPosition->y) {
                $this->myMove(inputManager::UP, inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT);
                $numberPosition = $this->grid->getPosition($number);
            }
        } else {
            while ($numberPosition->y > $toPosition->y) {
                $this->myMove(inputManager::DOWN, inputManager::LEFT, inputManager::UP, inputManager::RIGHT);
                $numberPosition = $this->grid->getPosition($number);
            }
        }
    }

    public function myAuto() {
        $size = $this->grid->size;
        $index = 0;
        $arrayOfOrder = $this->grid->getOrderOfPositionHaveToSet($this->grid->size);

        while ($size > 2) {
            for ($tmp = 0; $tmp < $size - 2; $tmp++) {
                $this->mySolveUpperLine($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1, $arrayOfOrder[$index]);
                $index++;
            }
            $this->mySolveUpperLine($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1, new position($arrayOfOrder[$index]->x, $arrayOfOrder[$index]->y + 1));
            $index++;

            if ($this->grid->getPosition($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1)->x == $this->grid->size - $size) { // 1 '3' 2와 같이 마지막 타일이 들어가 있는 상태. 직접 풀어낸다.
                $this->move(inputManager::UP);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::UP);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::UP);
                $this->move(inputManager::UP);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::UP);
                $this->move(inputManager::UP);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::DOWN);
            } else {
                $this->mySolveUpperLine($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1, new position($arrayOfOrder[$index]->x + 1, $arrayOfOrder[$index]->y));
                $this->myMove(inputManager::LEFT, inputManager::UP, inputManager::RIGHT, inputManager::DOWN);
            }
            $index++;

            for ($tmp = 1; $tmp < $size - 2; $tmp++) {
                $this->mySolveLefterLine($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1, $arrayOfOrder[$index]);
                $index++;
            }
            $this->mySolveLefterLine($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1, new position($arrayOfOrder[$index]->x + 1, $arrayOfOrder[$index]->y));
            $index++;
            if ($this->grid->getPosition($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1)->y == $this->grid->size - $size) { // 1 '3' 2와 같이 마지막 타일이 들어가 있는 상태. 직접 풀어낸다.
                $this->move(inputManager::LEFT);
                $this->move(inputManager::UP);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::UP);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::UP);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::RIGHT);
                $this->move(inputManager::UP);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::LEFT);
                $this->move(inputManager::DOWN);
                $this->move(inputManager::RIGHT);
            } else {
                $this->mySolveLefterLine($arrayOfOrder[$index]->x * $this->grid->size + $arrayOfOrder[$index]->y + 1, new position($arrayOfOrder[$index]->x, $arrayOfOrder[$index]->y + 1));
                $this->myMove(inputManager::UP, inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT);
            }
            $index++;

            $size--;
        }
        $this->move(inputManager::RIGHT);
        while (!($this->is_correct($this->grid))) {
            $this->move(inputManager::UP);
            $this->move(inputManager::LEFT);
            $this->move(inputManager::DOWN);
            $this->move(inputManager::RIGHT);
        }
    }
}


if (isset($_GET["grid"])) {
    $mGS = new myGridSolver($_GET["grid"]);
    $mGS->myAuto();
    echo $mGS->responseBody;
}
