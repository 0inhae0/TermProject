<?php
require_once "tile.php";

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
     * @var array
     */
    public $tiles;
    /**
     * @var array 빈 타일의 위치 정보. ["x" => $x, "y" => $y]의 형태.
     */
    public $nonePosition;
    /**
     * @var tile 빈 타일. 여러 계산상의 편리함 때문에 따로 저장해놓고자 함.
     */
    public $noneTile;

    /**
     * grid constructor.
     * @param int $size 슬라이딩 보드 크기
     */
    public function __construct(int $size) {
        $this->size = $size;
        $this->tiles = $this->addRandomTilesInit();
    }

    /**
     * @return array
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
                    $this->nonePosition = ['x' => $x, 'y' => $y];
                    $this->noneTile = new tile($this->nonePosition, 0);
                    array_push($rtn, $this->noneTile);
                }
                else array_push($rtn, new tile(['x' => $x, 'y' => $y], $inputValue[$x * $this->size + $y]));
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
        $has = false;
        foreach ($this->tiles as $tile) {
            if ($tile->isEqual($tile1)) {
                $has = true;
                $tileToSwap1 = &$tile;
                break;
            }
        }
        if (!($has)) return;

        $tmp = $tileToSwap1->x;
        $tileToSwap1->x = $this->nonePosition["x"];
        $this->nonePosition["x"] = $tmp;
        $tmp = $tileToSwap1->y;
        $tileToSwap1->y = $this->nonePosition["y"];
        $this->nonePosition["y"] = $tmp;
        $this->noneTile->x = $this->nonePosition["x"];
        $this->noneTile->y = $this->nonePosition["y"];
    }

    /**
     * @param array $position 클릭한 타일의 position 배열
     * 
     * noneTile과 수직, 수평상의 위치에 있는지 확인한 후,
     * 만약 그렇다면 인접한 tile끼리 위치를 바꾸는 것을 반복해서 이동시킨다.
     */
    public function moveTile(array $position) {
        foreach ($this->tiles as $tile) {
            if ($tile->x == $position["x"] && $tile->y == $position["y"]) {
                $tileClicked = $tile;
                break;
            }
        }
        if (!(isset($tileClicked))) {
            return;
        }

        if ($this->nonePosition["x"] == $tileClicked->x) {
            if ($this->nonePosition["y"] < $tileClicked->y) {
                while ($this->nonePosition["y"] < $tileClicked->y) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition["x"] && $tile->y == $this->nonePosition["y"] + 1) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
            elseif ($this->nonePosition["y"] > $tileClicked->y) {
                while ($this->nonePosition["y"] > $tileClicked->y) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition["x"] && $tile->y == $this->nonePosition["y"] - 1) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
        }
        elseif ($this->nonePosition["y"] == $tileClicked->y) {
            if ($this->nonePosition["x"] < $tileClicked->x) {
                while ($this->nonePosition["x"] < $tileClicked->x) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition["x"] + 1 && $tile->y == $this->nonePosition["y"]) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            } elseif ($this->nonePosition["x"] > $tileClicked->x) {
                while ($this->nonePosition["x"] > $tileClicked->x) {
                    foreach ($this->tiles as $tile) {
                        if ($tile->x == $this->nonePosition["x"] - 1 && $tile->y == $this->nonePosition["y"]) {
                            $this->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
        }
    }
}