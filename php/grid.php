<?php

/**
 * Class grid
 * 화면에 표시되는 슬라이딩 보드의 기본 격자 구조 클래스
 */
class grid {
    /**
     * @var int 슬라이딩 보드의 가로 및 세로 길이
     * ex) 3*3 보드는 3
     */
    public $size;
    /**
     * @var tile[] 슬라이딩 보드의 타일들을 저장하는 배열
     */
    public $tiles;
    /**
     * @var array 빈 타일의 위치. ["x" => var, "y" => var]의 형태.
     */
    public $positionNone;
    /**
     * @var tile 빈 타일
     */
    public $tileNone;

    /**
     * grid constructor.
     * @param int $size
     * @param null $previousState 이전 보드의 상태
     * 만약 사이트에 접속해도 이전 보드상태를 유지하고 싶을 경우 이 값을 이용할 예정
     */
    public function __construct(int $size, $previousState = null) {
        if ($size < 3 or $size > 10) {
            $size = 4;
        }
        $this->size = $size;
        $this->tiles = isset($previousState) ? $previousState : $this->addRandomTilesInit();
    }

    /**
     * @return tile[] 타일의 위치를 무작위로 정해서 추가한 배열
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
                $isNone = false;
                if ($inputValue[$x * $this->size + $y] == 0) {
                    // Value가 0인 타일을 빈 타일로 하기로 합시다.
                    $this->positionNone = ['x' => $x, 'y' => $y];
                    $isNone = true;
                }
                if ($isNone) {
                    $this->tileNone = new tile($this->positionNone, 0);
                    array_push($rtn, $this->tileNone);
                }
                else array_push($rtn, new tile(['x' => $x, 'y' => $y], $inputValue[$x * $this->size + $y]));
            }
        }
        return $rtn;
    }

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
        $tileToSwap1->x = $this->positionNone["x"];
        $this->positionNone["x"] = $tmp;
        $tmp = $tileToSwap1->y;
        $tileToSwap1->y = $this->positionNone["y"];
        $this->positionNone["y"] = $tmp;
        $this->tileNone->x = $this->positionNone["x"];
        $this->tileNone->y = $this->positionNone["y"];
    }
}