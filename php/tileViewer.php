<?php
/**
 * Class tileViewer
 * 화면에 슬라이딩 타일들을 추가하는 html 태그 및 내용을 생성하는 클래스
 */
class tileViewer {
    /**
     * @var int
     * 슬라이딩 보드 크기
     */
    public $gridsize;
    /**
     * @var int
     * 타일의 가로, 세로 크기
     * 단위는 px.
     */
    public $tileWidth;
    /**
     * @var grid
     * 타일을 추가할 바탕이 되는 grid 객체
     */
    public $grid;

    /**
     * tileViewer constructor.
     * @param int $size 전체 보드의 크기
     */
    public function __construct(int $size, string $previousGridToString = null, array $positionClicked = null) {
        if (isset($previousGridToString)) {
            $this->grid = unserialize((htmlspecialchars_decode($previousGridToString)));
            $this->gridsize = $this->grid->size;
            $this->tileWidth = (500 - (20 * ($this->gridsize + 1))) / $this->gridsize;
            $this->moveTile($positionClicked);
        }
        else {
            $this->gridsize = $size;
            $this->tileWidth = (500 - (20 * ($this->gridsize + 1))) / $this->gridsize;
            $this->grid = new grid($this->gridsize);
        }
        /**
         * tileWidth 계산 방식
         * (500(전체 보드 가로, 세로 크기) - (20(타일간의 여백) * (타일간의 여백의 개수)) / (한 줄 당 타일의 개수)
         * = 한 타일의 가로, 세로 크기
         */
        usort($this->grid->tiles, function (tile $a, tile $b) {
           $retval = $a->x <=> $b->x;
           if ($retval == 0) {
               $retval = $a->y <=> $b->y;
           }
           return $retval;
        });
        $this->actuate($this->grid);
    }

    /**
     * @param grid $grid 실행할 기본 grid
     */
    public function actuate(grid $grid) {
        echo ("\t\t\t\t".'<input type="hidden" name="sizeInput" value="'.$this->gridsize.'">'."\n");
        $string = htmlspecialchars((serialize($grid)));
        echo ("\t\t\t\t".'<input type="hidden" name="previousGrid" value="'.$string.'">'."\n");
        foreach ($grid->tiles as $tile) {
            $this->addTile($tile);
        }
    }

    /**
     * @param tile $tile html 형식으로 추가할 tile
     */
    public function addTile(tile $tile) {
        //$classes = ["tile", "tile-".$tile->value, "tile-position-".(($tile->x)+1)."-".(($tile->y)+1)];
        //$classesHTMLForm = join(" ", $classes);
        $classesHTMLForm = "tile tile-position-".($tile->x)."-".($tile->y)." tile-".$tile->value;
        $styleHTMLForm = "width: ".$this->tileWidth."px; height:".$this->tileWidth."px;".' font-size: '.(int)(($this->tileWidth)/3).'px;'." line-height: ".$this->tileWidth."px;";
        $tileValue = $tile->value;
        if ($tileValue == 0) $tileValue = "";
        echo ("\t\t\t\t".'<div class="'.$classesHTMLForm.'" style="'.$styleHTMLForm.'">'."\n");
        echo ("\t\t\t\t\t".'<input type="submit" class="tile-inner" name="'.($tile->x)."-".($tile->y).'" style="'.$styleHTMLForm.'" value='.$tileValue.'>'."\n");
        echo ("\t\t\t\t".'</div>'."\n");
    }

    public function moveTile(array $position) {
        foreach ($this->grid->tiles as $tile) {
            if ($tile->x == $position["x"] && $tile->y == $position["y"]) {
                $tileClicked = $tile;
                break;
            }
        }
        if (!(isset($tileClicked))) {
            return;
        }

        if ($this->grid->positionNone["x"] == $tileClicked->x) {
            if ($this->grid->positionNone["y"] < $tileClicked->y) {
                while ($this->grid->positionNone["y"] < $tileClicked->y) {
                    foreach ($this->grid->tiles as $tile) {
                        if ($tile->x == $this->grid->positionNone["x"] && $tile->y == $this->grid->positionNone["y"] + 1) {
                            $this->grid->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
            elseif ($this->grid->positionNone["y"] > $tileClicked->y) {
                while ($this->grid->positionNone["y"] > $tileClicked->y) {
                    foreach ($this->grid->tiles as $tile) {
                        if ($tile->x == $this->grid->positionNone["x"] && $tile->y == $this->grid->positionNone["y"] - 1) {
                            $this->grid->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
        }
        elseif ($this->grid->positionNone["y"] == $tileClicked->y) {
            if ($this->grid->positionNone["x"] < $tileClicked->x) {
                while ($this->grid->positionNone["x"] < $tileClicked->x) {
                    foreach ($this->grid->tiles as $tile) {
                        if ($tile->x == $this->grid->positionNone["x"] + 1 && $tile->y == $this->grid->positionNone["y"]) {
                            $this->grid->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            } elseif ($this->grid->positionNone["x"] > $tileClicked->x) {
                while ($this->grid->positionNone["x"] > $tileClicked->x) {
                    foreach ($this->grid->tiles as $tile) {
                        if ($tile->x == $this->grid->positionNone["x"] - 1 && $tile->y == $this->grid->positionNone["y"]) {
                            $this->grid->swapWithNoneTile($tile);
                            break;
                        }
                    }
                }
            }
        }
    }
}
?>
