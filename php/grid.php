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
     * @return tile[]
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
                array_push($rtn, new tile(["x" => $x, "y" => $y], $inputValue[$x * $this->size + $y]));
            }
        }
        return $rtn;
    }

    public function availableCells() {
        return $this->tiles;
    }

    public function randomAvailableCell() {
        $cells = $this->availableCells();
        if (count($cells) != 0) {
            return $cells[rand(0, count($cells) - 1)];
        }
        return null;
    }

    public function serialize() {
        $cellState = array();

        for ($x = 0; $x < $this->size; $x++) {
            $row = $cellState[$x] = [];

            for ($y = 0; $y < $this->size; $y++) {
                array_push($row, $this->tiles[$x][$y] ? $this->tiles[$x][$y] : null);
            }
        }

        return ["size" => $this->size, "tiles" => $cellState];
    }

    public function insertTile(tile $tileToInsert) {
        $isValid = true;
        foreach ($this->tiles as $tile) {
            if ($tile->x == $tileToInsert->x and $tile->y == $tileToInsert->y) {
                $isValid = false;
                break;
            }
        }
        if ($isValid) array_push($this->tiles, $tileToInsert);
    }

    public function tilesAvailable() {
        return count($this->availableCells()) < $this->size * $this->size;
    }
}