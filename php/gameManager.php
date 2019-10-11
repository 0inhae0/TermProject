<?php
class gameManager {
    public $size;
    public $grid;
    public $score;
    public $over;
    public $won;
    public $post;
    public $actuator;

    function __construct() {
        $this->post = $_POST;
        $this->size = isset($this->post["size"]) ? $this->post["size"] : 4;
        $this->grid = new grid($this->size);
        $this->actuator = new htmlActuator();
        $this->setup();
    }

    public function restart() {

    }

    public function setup() {
        $this->score = 0;
        $this->over = false;
        $this->won = false;

        $this->addStartTiles();

        $this->actuate();
    }

    public function addStartTiles() {
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                $this->addTile($x * $this->size + $y);
            }
        }
    }

    public function addTile($value) {
        if ($this->grid->cellsAvailable()) {
            $tile = new tile($this->grid->randomAvailableCell(), $value);

            $this->grid->insertTile($tile);
        }
    }

    public function actuate() {
        $this->actuator->actuate($this->grid,
            ["score" => $this->score,
            "over" => $this->over,
            "won" => $this->won,
            "terminated" => $this->isGameTerminated()]);
    }

    public function isGameTerminated() {
        return ($this->over or $this->won);
    }
}
