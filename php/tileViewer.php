<?php
class tileViewer {
    public $gridsize;
    public $tileWidth;
    public $grid;

    public function __construct(int $size) {
        $this->gridsize = $size;
        $this->tileWidth = (500 - (20 * ($this->gridsize + 1))) / $this->gridsize;
        $this->grid = new grid($this->gridsize);
        $this->setup();
        $this->actuate($this->grid);
    }

    public function setup() {
        $this->addStartTiles();
    }

    public function addStartTiles() {
        for ($x = 0; $x < $this->gridsize; $x++) {
            for ($y = 0; $y < $this->gridsize; $y++) {
                $this->addRandomTile($x * $this->gridsize + $y);
            }
        }
    }

    public function addRandomTile(int $value) {
        if ($this->grid->tilesAvailable()) {
            $tile = new tile($this->grid->randomAvailableCell(), $value);
            $this->grid->insertTile($tile);
        }
    }

    public function actuate(grid $grid) {
        foreach ($grid->tiles as $tile) {
            $this->addTile($tile);
        }
    }

    public function addTile(tile $tile) {
        //$classes = ["tile", "tile-".$tile->value, "tile-position-".(($tile->x)+1)."-".(($tile->y)+1)];
        //$classesHTMLForm = join(" ", $classes);
        $classesHTMLForm = "tile tile-".$tile->value." tile-position-".(($tile->x)+1)."-".(($tile->y)+1);
        $styleHTMLForm = "width: ".$this->tileWidth."px; height:".$this->tileWidth."px;";
        $tileValue = $tile->value;

        echo ("\t\t\t\t".'<div class="'.$classesHTMLForm.'" style="'.$styleHTMLForm.'">'."\n");
        echo ("\t\t\t\t\t".'<div class="tile-inner" style="'.$styleHTMLForm.'">'.$tileValue.'</div>'."\n");
        echo ("\t\t\t\t".'</div>'."\n");
    }
}
?>
