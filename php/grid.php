<?php
class grid {
    public $size;
    public $cells;

    public function __construct(int $size, $previousState = null) {
        $this->size = $size;
        $this->cells = $previousState ? $this->fromState($previousState) : $this->empty();
    }

    public function fromState($state) {
        $cells = array();

        for ($x = 0; $x < $this->size; $x++) {
            $row = $cells[$x] = [];

            for ($y = 0; $y < $this->size; $y++) {
                $tile = $state[$x][$y];
                $row[$y] = $tile ? new tile($tile->position, $tile->value) : null;
            }
        }

        return $cells;
    }

    public function empty() {
        $cells = array();

        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $tile = new tile(["x" => $x, "y" => $y], 0);
                array_push($cells, $tile);
            }
        }

        return $cells;
    }

    public function availableCells() {
        $cells = array();
        $this->eachCell(function ($x, $y, $tile) {
            if (!isset($tile)) {
                array_push($this->cells, new tile(["x" => $x, "y" => $y], 0));
            }
        });

        return $cells;
    }

    public function randomAvailableCell() {
        $cells = $this->availableCells();

        if (count($cells) != 0) {
            return $cells[rand(0, count($cells) - 1)];
        }
        return null;
    }

    public function cellsAvailable() {
        return count($this->availableCells()) != 0;
    }

    public function eachCell($callback) {
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $callback($x, $y, new tile(["x" => $x, "y" => $y], 0));
            }
        }
    }

    public function serialize() {
        $cellState = array();

        for ($x = 0; $x < $this->size; $x++) {
            $row = $cellState[$x] = [];

            for ($y = 0; $y < $this->size; $y++) {
                array_push($row, $this->cells[$x][$y] ? $this->cells[$x][$y] : null);
            }
        }

        return ["size" => $this->size, "cells" => $cellState];
    }

    public function insertTile(tile $tile) {
        $this->cells[$tile->x][$tile->y] = $tile;
    }
}