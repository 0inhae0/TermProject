<?php
require_once "simpleRequire.php";

/**
 * Class grid 슬라이딩 보드의 상태를 표현하는 클래스
 */
class grid {
    /**
     * @var int 슬라이딩 보드의 크기
     *
     * 3 x 3 크기의 보드면 3, 4 x 4 크기의 보드면 4, ..., 10 x 10 크기의 보드면 10
     */
    public $size;
    /**
     * @var int[][]
     */
    public $tiles;

    /**
     * @var position
     */
    public $nonePosition;

    /**
     * grid constructor.
     * @param int $size 슬라이딩 보드 크기
     */
    public function __construct(int $size) {
        $this->size = $size;
        $this->tiles = $this->addRandomTilesInit();
        if (!($this->isSolvable())) {
            for ($x = 0; $x < $size; $x++) {
                if ($this->nonePosition->getX() != $x) {
                    $nP = $this->nonePosition;
                    $this->swapWithNoneTile(new position($x, 0));
                    $this->swapWithNoneTile(new position($x, 1));
                    $this->swapWithNoneTile($nP);
                    break;
                }
            }
        }
    }

    public function addRandomTilesInit() {
        $rtn = array(array());
        $inputValue = array();
        for ($i = 0; $i < $this->size * $this->size; $i++) {
            array_push($inputValue, $i);
        }
        shuffle($inputValue);
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $rtn[$x][$y] = $inputValue[$x * $this->size + $y];
                if (($inputValue[$x * $this->size + $y]) == 0) {
                    $this->nonePosition = new position($x, $y);
                }
            }
        }
        return $rtn;
    }

    /**
     * @param position $position
     */
    public function swapWithNoneTile(position $position) {
        $tmp = $this->tiles[$position->getX()][$position->getY()];
        $this->tiles[$position->getX()][$position->getY()] = $this->tiles[$this->nonePosition->getX()][$this->nonePosition->getY()];
        $this->tiles[$this->nonePosition->getX()][$this->nonePosition->getY()] = $tmp;
        $this->nonePosition = $position;
    }

    /**
     * @param position $position 클릭한 타일의 position 배열
     *
     * noneTile과 수직, 수평상의 위치에 있는지 확인한 후,
     * 만약 그렇다면 인접한 tile끼리 위치를 바꾸는 것을 반복해서 이동시킨다.
     */
    public function moveTile(position $position) {
        if ($position->isEqual($this->nonePosition)) return;

        if ($this->nonePosition->getX() == $position->getX()) {
            while ($this->nonePosition->getY() < $position->getY()) {
                $this->swapWithNoneTile(new position($this->nonePosition->getX(), $this->nonePosition->getY() + 1));
            }
            while ($this->nonePosition->getY() > $position->getY()) {
                $this->swapWithNoneTile(new position($this->nonePosition->getX(), $this->nonePosition->getY() - 1));
            }
        }
        else if ($this->nonePosition->getY() == $position->getY()) {
            while ($this->nonePosition->getX() < $position->getX()) {
                $this->swapWithNoneTile(new position($this->nonePosition->getX() + 1, $this->nonePosition->getY()));
            }
            while ($this->nonePosition->getX() > $position->getX()) {
                $this->swapWithNoneTile(new position($this->nonePosition->getX() - 1, $this->nonePosition->getY()));
            }
        }
    }

    public function moveTileWithDirection(string $DIRECTION) {
        $inputManager = new inputManager();
        $inputManager->move($this, $DIRECTION);
    }
    public function moveTileAuto() {
        //$inputManager = new inputManager();
        //$gridSolver = new gridSolver($this);
        //$gridSolver->auto($this);
        $myGridSolver = new myGridSolver($this);
        $myGridSolver->myAuto();
    }

    public function isSolvable() {
        /**
         * https://www.cs.bham.ac.uk/~mdr/teaching/modules04/java2/TilesSolvability.html
         * Fact 3에 기반한 확인 절차.
         */
        $invCount = 0;
        for ($i = 0; $i < pow($this->size, 2) - 1; $i++) {
            $ixValue = (int)($i / $this->size);
            $iyValue = (int)($i % $this->size);
            $iTileValue = $this->tiles[$ixValue][$iyValue];
            for ($j = $i + 1; $j < pow($this->size, 2); $j++) {
                $jxValue = (int)($j / $this->size);
                $jyValue = (int)($j % $this->size);
                $jTileValue = $this->tiles[$jxValue][$jyValue];
                if (($iTileValue > $jTileValue) && ($jTileValue != 0)) {
                    $invCount++;
                }
            }
        }
        if ($this->size % 2 == 1) {
            return ($invCount % 2 == 0);
        }
        else {
            return (($invCount + ($this->size - $this->nonePosition->getX())) % 2 == 1) ? true : false;
        }
    }

    /**
     * @param $size
     * @return position[]
     */
    public function getOrderOfPositionHaveToSet($size) {
        $rtn = array();
        for ($x = 0; $x < $size; $x++) {
            for ($y = $x; $y < $size; $y++) {
                array_push($rtn, new position($x, $y));
            }
            for ($y = $x + 1; $y < $size; $y++) {
                array_push($rtn, new position($y, $x));
            }
        }
        return $rtn;
    }

    public function getPosition($num) {
        if (($num >= 0) && ($num < pow($this->size, 2))) {
            for ($x = 0; $x < $this->size; $x++) {
                for ($y = 0; $y < $this->size; $y++) {
                    if ($this->tiles[$x][$y] == $num) return new position($x, $y);
                }
            }
        }
        return false;
    }
}