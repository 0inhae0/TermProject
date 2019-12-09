<?php
class gridSolver {
    /**
     * @var int
     */
    public $num;
    /**
     * @var int
     */
    public $rec;
    /**
     * @var grid
     */
    public $grid;
    /**
     * @var position
     */
    public $n;
    /**
     * @var position
     */
    public $e;

    public function __construct(grid $grid) {
        $this->grid = $grid;
        $this->num = 0;
        $this->rec = 0;
        $this->n = new position();
        $this->e = new position();
    }

    public function move(grid $grid, string $direction) {
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
            $newX = $grid->nonePosition->getX() + $moveX;
            $newY = $grid->nonePosition->getY() + $moveY;
            if (($newX < $grid->size) && (0 <= $newX) && ($newY < $grid->size) && (0 <= $newY)) {
                $grid->moveTile(new position($newX, $newY));
            }
        }
        $this->n = $this->grid->getPosition($this->num);
    }

    public function is_correct(grid $grid){
        $num = 1;
        for ($i = 0; $i < $grid->size; $i++) {
            for($j=0;$j < $grid->size;$j++) {
                if ($grid->tiles[$i][$j] != $num && !($i == $grid->size - 1 && $j == $grid->size - 1))
                    return false;
                $num++;
            }
        }
        return true;
    }
    public function is_correct_line(grid $grid){
        $count = 0;
        $num = $this->rec * ($grid->size + 1) + 1;
        for ($j = $this->rec; $j < $grid->size - 2; $j++) {
            if ($grid->tiles[$this->rec][$j] != $num) {
                $count++;
                $grid->done[$this->rec][$j] = 0;
                break;
            } else
                $grid->done[$this->rec][$j] = 1;
            $num++;
        }
        if ($grid->done[$this->rec][$grid->size - 3] == 1) {
            for ($j = $grid->size - 2; $j < $grid->size - 1; $j++) {
                if ($grid->tiles[$this->rec][$j] == $num && $grid->tiles[$this->rec][$j + 1] == $num + 1) {
                    $grid->done[$this->rec][$j] = 1;
                    $grid->done[$this->rec][$j + 1] = 1;
                }
                else {
                    $count++;
                    $grid->done[$this->rec][$j] = 0;
                    $grid->done[$this->rec][$j + 1] = 0;
                }
            }
        }
        if ($count > 0)	return false;
        else return true;
    }
    public function is_correct_left(grid $grid)
    {
        $count = 0;
        $num = 0;
        for ($i = $this->rec + 1; $i < $grid->size - 2; $i++) {
            $num = 1 + $this->rec + $i * $grid->size;
            if ($grid->tiles[$i][$this->rec] != $num) {
                $count++;
                $grid->done[$i][$this->rec] = 0;
                break;
            } else
                $grid->done[$i][$this->rec] = 1;
            $num++;
        }
        if ($grid->done[$grid->size - 3][$this->rec] == 1) {
            for ($i = $grid->size - 2; $i < $grid->size - 1; $i++) {
                if ($grid->tiles[$i][$this->rec] == $num && $grid->tiles[$i+1][$this->rec] ==  1 + $this->rec + ($i + 1) * $grid->size) {
                    $grid->done[$i][$this->rec] = 1;
                    $grid->done[$i+1][$this->rec + 1] = 1;
                } else {
                    $count++;
                    $grid->done[$i][$this->rec] = 0;
                    $grid->done[$i+1][$this->rec] = 0;
                }
            }
        }
        if ($count > 0) return false;
        else return true;
    }

    public function AutoMove(grid $grid, $key1, $key2, $key3, $key4) {
        if ((($key3 == inputManager::DOWN || $key3 == inputManager::UP) && $this->n->x == $this->e->x) || (($key3 == inputManager::LEFT || $key3 == inputManager::RIGHT) && $this->n->y == $this->e->y)) {
            if( ($key3 == inputManager::LEFT &&$key2 ==inputManager::DOWN &&$this->e->x < $this->n->x)||($key3 == inputManager::RIGHT && $key2 == inputManager::DOWN && $this->e->x < $this->n->x))
                $this->move($grid, $key2);//key3 == 'r'&&arr->e->x<tile->n->$this->x)||
            if ((($key3 == inputManager::DOWN || $key3 == inputManager::UP) && $key4==inputManager::LEFT && $this->n->x == $this->e->x) && $this->e->y < $this->n->y) {
                $this->move($grid, $key3);
                $this->move($grid, $key2);
                $this->move($grid, $key1);
            }
            else {
                $this->move($grid, $key3);
                $this->move($grid, $key4);
                $this->move($grid, $key1);
            }
        }
        else {
            $this->move($grid, $key2);
            $this->move($grid, $key3);
            $this->move($grid, $key3);
            $this->move($grid, $key4);
            $this->move($grid, $key1);
        }
        $this->n = $grid->getPosition($this->num);
    }
    public function AutoMoveM(grid $grid, $key1, $key2, $key3, $key4) {
        switch ($key3)
        {
            case inputManager::UP:
                if ($this->e->x < $this->n->x) $this->move($grid, $key1);
                else $this->AutoMove($grid, $key1, $key2, $key3, $key4);
                break;
            case inputManager::DOWN:
                if ($this->e->x > $this->n->x) $this->move($grid, $key1);
                else $this->AutoMove($grid, $key1, $key2, $key3, $key4);
                break;
            case inputManager::LEFT:
                if ($this->e->y < $this->n->y) $this->move($grid, $key1);
                else $this->AutoMove($grid, $key1, $key2, $key3, $key4);
                break;
            case inputManager::RIGHT:
                if ($this->e->y > $this->n->y) $this->move($grid, $key1);
                else $this->AutoMove($grid, $key1, $key2, $key3, $key4);
                break;
        }
        $this->n = $this->grid->getPosition($this->num);
    }
    public function hor_first(grid $grid,$sx, $sy){
        while ($grid->tiles[$sx][$sy] != $this->num) {
            $this->n = $this->grid->getPosition($this->num);
            if ($sy < $this->n->y) {
                if ($this->n->x != $grid->size - 1 && $grid->done[$this->n->x][$this->n->y - 1] != 1 && $grid->done[$this->e->x][$this->e->y - 1] != 1) { //아래가 뚫려있고 왼쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::RIGHT, inputManager::DOWN, inputManager::LEFT, inputManager::UP);
                }
                else if ($this->n->x == $grid->size - 1 && $grid->done[$this->n->x][$this->n->y - 1] != 1) {//위가 뚫려있고 왼쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::RIGHT, inputManager::UP, inputManager::LEFT, inputManager::DOWN);
                }
                else if ($this->e->x < $this->n->x) {
                    $this->move($grid, inputManager::RIGHT);
                    $this->move($grid, inputManager::DOWN);
                    $this->move($grid, inputManager::DOWN);
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::UP);
                    $this->move($grid, inputManager::RIGHT);
                }
            }
            else if ($sy > $this->n->y) {
                if ($this->n->x != $grid->size - 1) {//아래가 뚫려있고 오른쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT, inputManager::UP);
                }
                else {//위가 뚫려있고 오른쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::LEFT, inputManager::UP, inputManager::RIGHT, inputManager::DOWN);
                }
            }
            else if ($sx < $this->n->x) {
                if ($this->n->y != $grid->size - 1 && $grid->done[$this->e->x - 1][$this->e->y] != 1) { //오른쪽이 뚫려있고 위로 가야할때
                    $this->AutoMoveM($grid, inputManager::DOWN, inputManager::RIGHT, inputManager::UP, inputManager::LEFT);
                }
                else if ($this->n->y == $grid->size - 1) { //왼쪽이 뚫려있고 위로 가야할때
                    $this->AutoMoveM($grid, inputManager::DOWN, inputManager::LEFT, inputManager::UP, inputManager::RIGHT);
                }
                else if ($this->e->y < $this->n->y) {
                    $this->move($grid, inputManager::DOWN);
                    $this->move($grid, inputManager::RIGHT);
                    $this->move($grid, inputManager::RIGHT);
                    $this->move($grid, inputManager::UP);
                    $this->move($grid, inputManager::UP);
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::DOWN);
                }
            }
            else if ($sx > $this->n->x) {
                if ($this->n->y != $grid->size - 1) { //오른쪽이 뚫려있고 아래로 가야할때
                    $this->AutoMoveM($grid, inputManager::UP, inputManager::RIGHT, inputManager::DOWN, inputManager::LEFT);
                }
                else { //왼쪽이 뚫려있고 아래로 가야할때
                    $this->AutoMoveM($grid, inputManager::UP, inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT);
                }
            }
            $this->e = $grid->nonePosition;
        }
    }
    public function ver_first(grid $grid,$sx, $sy){
        while ($grid->tiles[$sx][$sy] != $this->num) {
            $this->n = $this->grid->getPosition($this->num);
            if ($sx < $this->n->x) {
                if ($this->n->y != $grid->size - 1) { //오른쪽이 뚫려있고 위로 가야할때
                    $this->AutoMoveM($grid, inputManager::DOWN, inputManager::RIGHT, inputManager::UP, inputManager::LEFT);
                }
                else if ($this->n->y == $grid->size - 1) { //왼쪽이 뚫려있고 위로 가야할때
                    $this->AutoMoveM($grid, inputManager::DOWN, inputManager::LEFT, inputManager::UP, inputManager::RIGHT);
                }
                else if ($this->e->y < $this->n->y) {
                    $this->move($grid, inputManager::DOWN);
                    $this->move($grid, inputManager::RIGHT);
                    $this->move($grid, inputManager::RIGHT);
                    $this->move($grid, inputManager::UP);
                    $this->move($grid, inputManager::UP);
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::DOWN);
                }
            }
            else if ($sx > $this->n->x) {
                if ($this->n->y != $grid->size - 1) { //오른쪽이 뚫려있고 아래로 가야할때
                    $this->AutoMoveM($grid, inputManager::UP, inputManager::RIGHT, inputManager::DOWN, inputManager::LEFT);
                }
                else { //왼쪽이 뚫려있고 아래로 가야할때
                    $this->AutoMoveM($grid, inputManager::UP, inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT);
                }
            }
            else if ($sy < $this->n->y) {
                if ($this->n->x != $grid->size - 1) { //아래가 뚫려있고 왼쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::RIGHT, inputManager::DOWN, inputManager::LEFT, inputManager::UP);
                }
                else if ($this->n->x == $grid->size - 1) {//위가 뚫려있고 왼쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::RIGHT, inputManager::UP, inputManager::LEFT, inputManager::DOWN);
                }
                else if ($this->e->x < $this->n->x) {
                    $this->move($grid, inputManager::RIGHT);
                    $this->move($grid, inputManager::DOWN);
                    $this->move($grid, inputManager::DOWN);
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::UP);
                    $this->move($grid, inputManager::RIGHT);
                }
            }
            else if ($sy > $this->n->y) {
                if ($this->n->x != $grid->size - 1) {//아래가 뚫려있고 오른쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::LEFT, inputManager::DOWN, inputManager::RIGHT, inputManager::UP);
                }
                else {//위가 뚫려있고 오른쪽으로 가야할때
                    $this->AutoMoveM($grid, inputManager::LEFT, inputManager::UP, inputManager::RIGHT, inputManager::DOWN);
                }
            }
        }
    }

    public function moves(grid $grid,$sx, $sy,$t) {
        echo "this.num = $this->num"."<br>";
        $count = 0;
        $this->e = $grid->nonePosition;

        if (($sx == $sy || $sy < $grid->size - 2 && $t==0) || ($sx < $grid->size - 2 && $t==1)) {
            if ($grid->tiles[$sx][$sy] == $this->num) return;
            while ($grid->tiles[$sx][$sy] != 0) { //빈칸을 목표위치로 이동시킴
                if ($sy < $this->e->y) {
                    $this->move($grid, inputManager::LEFT);
                    $this->e->y--;
                }
                else if ($sy > $this->e->y) {
                    $this->move($grid, inputManager::RIGHT);
                    $this->e->y++;
                }
                else if ($sx < $this->e->x) {
                    $this->move($grid, inputManager::UP);
                    $this->e->x--;
                }
                else if ($sx > $this->e->x) {
                    $this->move($grid, inputManager::DOWN);
                    $this->e->y++;
                }
            }
        }
        else if ($t == 0) {
            if ($sy == $grid->size - 1) {
                $sx = $sx + 1;
                $count = 1;
            }
            else
                $sy = $sy + 1;
        }
        else {
            if ($sx == $grid->size - 1) {
                $count = 1;
                $sy = $sy + 1;
            }
            else
                $sx = $sx + 1;
        }
        $this->n = $this->grid->getPosition($this->num);
        while ($grid->tiles[$this->n->x][$this->n->y] != 0) { //빈칸을 목표위치로 이동시킴
            if ($this->n->y > $this->e->y) {
                $this->move($grid, inputManager::RIGHT);
                $this->e->y++;
            }
            else if ($this->n->y < $this->e->y) {
                $this->move($grid, inputManager::LEFT);
                $this->e->y--;
            }
            else if ($this->n->x < $this->e->x) {
                $this->move($grid, inputManager::UP);
                $this->e->x--;
            }
            else if ($this->n->x > $this->e->x) {
                $this->move($grid, inputManager::DOWN);
                $this->e->x++;
            }
        }
        $this->n = $this->grid->getPosition($this->num);
        if($t==0) $this->hor_first($grid, $sx, $sy);
        else $this->ver_first($grid, $sx, $sy);
        if ($sy >= $grid->size - 2&&$t==0) {
            if ($grid->tiles[$sx][$sy - 1] == ($this->num + 1) || ($grid->tiles[$sx + 1][$sy - 1] == ($this->num + 1) && $this->e->x < $sx + 1)) {
                if ($grid->tiles[$sx][$sy - 1] == ($this->num + 1)) {
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::UP);
                }
                $this->move($grid, inputManager::RIGHT);
                $this->move($grid, inputManager::DOWN);
                $this->move($grid, inputManager::DOWN);
                $this->move($grid, inputManager::LEFT);
                $this->move($grid, inputManager::UP);
                $this->move($grid, inputManager::RIGHT);
                $this->move($grid, inputManager::UP);
                $this->move($grid, inputManager::LEFT);
            }
            if ($count == 1) {
                if ($this->e->x > $this->n->x) {
                    $this->move($grid, inputManager::LEFT);
                    $this->move($grid, inputManager::UP);
                }

                $this->move($grid, inputManager::UP);
                $this->move($grid, inputManager::RIGHT);
                $this->move($grid, inputManager::DOWN);
            }
        }
        else if ($sx >= $grid->size - 2 && $t == 1) {
            if ($grid->tiles[$sx - 1][$sy] == ($this->num + $grid->size) || ($grid->tiles[$sx - 1][$sy + 1] == ($this->num + $grid->size) && $this->e->y < $sy + 1)) {
                if ($grid->tiles[$sx - 1][$sy] == ($this->num + $grid->size)) {
                    $this->move($grid,inputManager::UP);
                    $this->move($grid, inputManager::LEFT);
                }
                $this->move($grid, inputManager::DOWN);
                $this->move($grid, inputManager::RIGHT);
                $this->move($grid, inputManager::RIGHT);
                $this->move($grid, inputManager::UP);
                $this->move($grid, inputManager::LEFT);
                $this->move($grid, inputManager::DOWN);
                $this->move($grid, inputManager::LEFT);
                $this->move($grid, inputManager::UP);
            }
            if ($count == 1) {
                if ($this->e->y > $this->n->y) {
                    $this->move($grid, inputManager::UP);
                    $this->move($grid, inputManager::LEFT);
                }
                $this->move($grid, inputManager::LEFT);
                $this->move($grid, inputManager::DOWN);
                $this->move($grid, inputManager::RIGHT);
            }
        }
    }

    public function auto(grid $grid){
        $size = $grid->size;
        $index = -1;
        $arrayOfOrder = $grid->getOrderOfPositionHaveToSet($size);
        var_dump($arrayOfOrder);
        while ($size > 2) {
            echo "while loop at line ".__LINE__."<br>";
            $index++;
            $this->num = $arrayOfOrder[$index]->x * $grid->size + $arrayOfOrder[$index]->y + 1;
            if ($this->is_correct($grid))
                break;
            else {
                for ($j = $this->rec; $j < $grid->size; $j++) {
                    echo "for loop at line ".__LINE__."<br>";
                    if (!$this->is_correct_line($grid)) {
                        $this->moves($grid, $this->rec, $j, 0);
                    }
                    else
                        $j = $grid->size;
                    $index++;
                    $this->num = $arrayOfOrder[$index]->x * $grid->size + $arrayOfOrder[$index]->y + 1;
                }
                for ($i = $this->rec + 1; $i < $grid->size; $i++) {
                    echo "for loop at line ".__LINE__."<br>";
                    if (!$this->is_correct_left($grid)) {
                        $this->moves($grid, $i,$this->rec, 1);
                    }
                    $index++;
                    $this->num = $arrayOfOrder[$index]->x * $grid->size + $arrayOfOrder[$index]->y + 1;
                }
            }
            $this->rec++;
            $size--;
        }
        $this->move($grid, inputManager::DOWN);
        $this->move($grid, inputManager::RIGHT);
        while (!($this->is_correct($grid))) {
            echo "while loop at line ".__LINE__;
            $this->move($grid, inputManager::UP);
            $this->move($grid, inputManager::LEFT);
            $this->move($grid, inputManager::DOWN);
            $this->move($grid, inputManager::RIGHT);
        }
        // is_e->xact_suburb(arr, rec);
        // is_e->xact_line(arr, rec);
    }
}