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
     * @var tile[]
     */
    public $tiles;
    /**
     * @var position
     */
    public $nonePosition;
    /**
     * @var tile 빈 타일. 여러 계산상의 편리함 때문에 따로 저장해놓고자 함.
     */
    public $noneTile;
    /**
     * @var tileMover
     */
    public $tileMover;

    /**
     * grid constructor.
     * @param int $size 슬라이딩 보드 크기
     */
    public function __construct(int $size) {
        $this->size = $size;
        $this->tiles = $this->addRandomTilesInit();
        if (!($this->isSolvable())) {
            foreach ($this->tiles as $tile1) {
                if ($tile1->value != 0) {
                    foreach ($this->tiles as $tile2) {
                        if ($tile2->value != 0) {
                            $this->swapWithNoneTile($tile1);
                            $this->swapWithNoneTile($tile2);
                            $this->swapWithNoneTile($tile1);
                        }
                    }
                    break;
                }
            }
        }
        $this->tileMover = new tileMover($this);
    }

    /**
     * @return tile[]
     * 
     * tile의 value를 무작위 순서로 정하여 rtn 배열에 추가한 후 반환
     */
    public function addRandomTilesInit() {
        $rtn = array();
        $inputValue = array();
        for ($i = 0; $i < $this->size * $this->size; $i++) {
            array_push($inputValue, $i);
        }
        shuffle($inputValue);
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                if ($inputValue[$x * $this->size + $y] == 0) {
                    $this->nonePosition = new position($x, $y);
                    $this->noneTile = new tile($this->nonePosition, 0);
                    array_push($rtn, $this->noneTile);
                }
                else array_push($rtn, new tile(new position($x, $y), $inputValue[$x * $this->size + $y]));
            }
        }
        return $rtn;
    }

    /**
     * tiles 배열을 정렬하는 함수
     * HTML에서 화면에 표시할 때 순서에 맞춰 tile이 배치되도록 하게 하기 위함
     */
    public function sortTiles() {
        usort($this->tiles, function (tile $a, tile $b) {
            $rtn = $a->x <=> $b->x;
            if ($rtn == 0) {
                $rtn = $a->y <=> $b->y;
            }
            return $rtn;
        });
    }

    /**
     * @param tile $tile1 noneTile과 위치를 바꿀 tile
     *
     * 입력받은 tile과 noneTile의 x, y값을 서로 바꾼다.
     * 보드판에서 클릭했을 때 tile이 이동하는 것을 이 함수를 반복하는 것으로 구현.
     */
    public function swapWithNoneTile(tile $tile1) {
        foreach ($this->tiles as $tile) {
            if ($tile->isEqual($tile1)) {
                $tileToSwap1 = &$tile;
                break;
            }
        }
        if (!(isset($tileToSwap1))) return;

        $tmp = $tileToSwap1->x;
        $tileToSwap1->x = $this->nonePosition->getX();
        $this->nonePosition->setX($tmp);
        $tmp = $tileToSwap1->y;
        $tileToSwap1->y = $this->nonePosition->getY();
        $this->nonePosition->setY($tmp);
        $this->noneTile->x = $this->nonePosition->getX();
        $this->noneTile->y = $this->nonePosition->getY();
    }

    /**
     * @param position $position 클릭한 타일의 position 배열
     *
     * noneTile과 수직, 수평상의 위치에 있는지 확인한 후,
     * 만약 그렇다면 인접한 tile끼리 위치를 바꾸는 것을 반복해서 이동시킨다.
     */
    public function moveTile(position $position) {
        foreach ($this->tiles as $tile) {
            if ($tile->x == $position->getX() && $tile->y == $position->getY()) {
                $tileClicked = $tile;
                break;
            }
        }
        if (!(isset($tileClicked))) {
            return;
        }

        if ($this->nonePosition->getX() == $tileClicked->x) {
            if ($this->nonePosition->getY() < $tileClicked->y) {
                while ($this->nonePosition->getY() < $tileClicked->y) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition->getX() && $tile->y == $this->nonePosition->getY() + 1) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
            elseif ($this->nonePosition->getY() > $tileClicked->y) {
                while ($this->nonePosition->getY() > $tileClicked->y) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition->getX() && $tile->y == $this->nonePosition->getY() - 1) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
        }
        elseif ($this->nonePosition->getY() == $tileClicked->y) {
            if ($this->nonePosition->getX() < $tileClicked->x) {
                while ($this->nonePosition->getX() < $tileClicked->x) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition->getX() + 1 && $tile->y == $this->nonePosition->getY()) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            } elseif ($this->nonePosition->getX() > $tileClicked->x) {
                while ($this->nonePosition->getX() > $tileClicked->x) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition->getX() - 1 && $tile->y == $this->nonePosition->getY()) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
        }
    }

    public function moveTileWithDirection(string $DIRECTION) {
        $this->tileMover->move($DIRECTION);
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
            $iTileValue = $this->getValueWithXY($ixValue, $iyValue);
            for ($j = $i + 1; $j < pow($this->size, 2); $j++) {
                $jxValue = (int)($j / $this->size);
                $jyValue = (int)($j % $this->size);
                $jTileValue = $this->getValueWithXY($jxValue, $jyValue);
                if (($iTileValue > $jTileValue) && ($jTileValue != 0)) {
                    $invCount++;
                }
            }
        }
        if ($this->size % 2 == 1) {
            return ($invCount % 2 == 0) ? true : false;
        }
        else {
            return (($invCount + ($this->size - $this->nonePosition->getX())) % 2 == 1) ? true : false;
        }
    }

    public function getValueWithXY(int $x, int $y) {
        foreach ($this->tiles as $tile) {
            if ($tile->x == $x && $tile->y == $y) {
                return $tile->value;
            }
        }
        return false;
    }
}