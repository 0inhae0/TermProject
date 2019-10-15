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
    public function __construct(int $size) {
        $this->gridsize = $size;
        /**
         * tileWidth 계산 방식
         * (500(전체 보드 가로, 세로 크기) - (20(타일간의 여백) * (타일간의 여백의 개수)) / (한 줄 당 타일의 개수)
         * = 한 타일의 가로, 세로 크기
         */
        $this->tileWidth = (500 - (20 * ($this->gridsize + 1))) / $this->gridsize;
        $this->grid = new grid($this->gridsize);
        $this->addStartTiles();
        $this->actuate($this->grid);
    }

    /**
     * grid의 크기에 맞추어, 가능한 value값으로 addRandomTile 실행
     */
    public function addStartTiles() {
        for ($x = 0; $x < $this->gridsize; $x++) {
            for ($y = 0; $y < $this->gridsize; $y++) {
                $this->addRandomTile($x * $this->gridsize + $y);
            }
        }
    }

    /**
     * @param int $value $grid에 추가할 $tile의 value 값
     */
    public function addRandomTile(int $value) {
        if ($this->grid->tilesAvailable()) {
            $tile = new tile($this->grid->randomAvailableCell(), $value);
            $this->grid->insertTile($tile);
        }
    }

    /**
     * @param grid $grid 실행할 기본 grid
     */
    public function actuate(grid $grid) {
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
        $classesHTMLForm = "tile tile-".$tile->value." tile-position-".(($tile->x)+1)."-".(($tile->y)+1);
        $styleHTMLForm = "width: ".$this->tileWidth."px; height:".$this->tileWidth."px;".' font-size: '.(int)(($this->tileWidth)/3).'px;'." line-height: ".$this->tileWidth."px;";
        $tileValue = $tile->value;
        if ($tileValue == 0) $tileValue = "";
        echo ("\t\t\t\t".'<div class="'.$classesHTMLForm.'" style="'.$styleHTMLForm.'">'."\n");
        echo ("\t\t\t\t\t".'<div class="tile-inner" style="'.$styleHTMLForm.'">'.$tileValue.'</div>'."\n");
        echo ("\t\t\t\t".'</div>'."\n");
    }
}
?>
