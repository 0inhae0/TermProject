<?php

/**
 * Class grid
 */
class grid {
    /**
     * @var int size
     * @var tile[] tiles;
     */
    public $size;
    public $tiles;

    /**
     * grid constructor.
     * @param int $size
     * @param tile[] $previousState
     */
    public function __construct($size, $previousState = null) {
        $this->size = $size;
        $this->tiles = $previousState ? $this->fromState($previousState) : $this->addRandomTilesInit();
    }

    /**
     * @param tile[] $state
     * @return tile[]
     */
    public function fromState($state){
        for ($x = 0; $x < $this->size; $x++) {
            $row = $this->tiles[$x] = [];

            for ($y = 0; $y < $this->size; $y++) {
                $tile = $state[$x][$y];
                $row[$y] = $tile ? new tile($tile->position, $tile->value) : null;
            }
        }

        return $this->tiles;
    }

    /**
     * @return tile[]
     */
    public function addRandomTilesInit() {
        $this->tiles = array();
        $alreadyInputValue = array();
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $val = rand(0, $this->size * $this->size - 1);
                while (in_array($val, $alreadyInputValue)) {
                    $val = rand(0, $this->size * $this->size - 1);
                }
                array_push($this->tiles, new tile(["x" => $x, "y" => $y], $val));
                array_push($alreadyInputValue, $val);
            }
        }
        return $this->tiles;
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