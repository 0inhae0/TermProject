<?php
require_once "simpleRequire.php";

/**
 * Class htmlTransmitter HTML형식에 맞춰 index.php에서 출력하는 클래스.
 */
class htmlTransmitter {
    /**
     * div grid-container 항목 출력
     */
    public function transmitGridContainer() {
        echo '<div class="grid-container">'.PHP_EOL;
        $size = isset($_POST["sizeInput"]) ? $_POST["sizeInput"] : 4;
        $width = $this->getWidth($size);
        for ($i = 0; $i < $size; $i++) {
            echo '<div class="grid-row">'.PHP_EOL;
            for ($j = 0; $j < $size; $j++) {
                echo '<div class="grid-cell" style="width: '.$width.'px; height: '.$width.'px;"></div>'.PHP_EOL;
            }
            echo '</div>'.PHP_EOL;
        }
        echo '</div>'.PHP_EOL;
    }

    /**
     * @param $size int 보드의 크기
     * @return int 한 tile의 가로 및 세로 길이. px 단위.
     */
    public function getWidth($size) {
        // 전체 보드판의 크기가 500px
        // tile 사이의 간격이 20px
        return (int)((500 - (20 * ($size + 1))) / $size);
    }

    /**
     * @param gridViewer $gridViewer gridViewer에서 자기 자신의 정보를 넘겨서 호출.
     *
     * div tile-container의 코드.
     */
    public function transmitTileContainer(gridViewer $gridViewer) {
        echo '<div class="tile-container">'.PHP_EOL;
        echo '<form method="post" name="tile-container" action="'.$_SERVER['PHP_SELF'].'">'.PHP_EOL;
        echo '<style>'.'<?php require_once "style/main.css"; ?>'.'</style>'.PHP_EOL;

        $this->transmitTileContainer_Grid($gridViewer->grid);

        echo '</form>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
    }

    /**
     * @param grid $grid 표시할 grid
     *
     * sizeInput과 previousGrid를 POST로 전달하도록 함
     */
    public function transmitTileContainer_Grid(grid $grid) {
        $width = $this->getWidth($grid->size);
        echo '<input type="hidden" name="sizeInput" value="'.$grid->size.'">'.PHP_EOL;
        $serializedGrid = serialize($grid);
        echo '<input type="hidden" name="previousGrid" value="'.htmlspecialchars($serializedGrid).'">'.PHP_EOL;
        for ($x = 0; $x < $grid->size; $x++) {
            for ($y = 0; $y < $grid->size; $y++) {
                $this->transmitTileContainer_Tile(new position($x, $y), $width, $grid);
            }
        }
    }

    public function transmitTileContainer_Tile(position $position, int $width, grid $grid) {
        $HTMLFormClasses = "tile tile-position-".$position->getX()."-".$position->getY()." tile-".$grid->tiles[$position->getX()][$position->getY()];
        $HTMLFormStyle =
            "width: ".$width."px; ".
            "height: ".$width."px; ".
            "font-size: ".(int)($width/2)."px; ".
            "line-height: ".$width."px";

        $tileValue = $grid->tiles[$position->getX()][$position->getY()] != 0 ? $grid->tiles[$position->getX()][$position->getY()] : "";
        echo ('<div class="'.$HTMLFormClasses.'" style="'.$HTMLFormStyle.'">'.PHP_EOL);
        echo ('<input type="submit" class="tile-inner" name="'.($position->x)."-".($position->y).'" style="'.$HTMLFormStyle.'" value='.$tileValue.'>'.PHP_EOL);
        echo ("</div>".PHP_EOL);
    }

    public function transmitMoveDirection(grid $grid) {
        echo '
        <form method="post" name="moveDirection" class="direction" action="'.$_SERVER['PHP_SELF'].'">
            <input type="submit" name="direction" class="direction-button" value="AUTO">
            <input type="submit" name="direction" class="direction-button" value="'.inputManager::UP.'">
            <input type="submit" name="direction" class="direction-button" value="'.inputManager::RIGHT.'">
            <input type="submit" name="direction" class="direction-button" value="'.inputManager::DOWN.'">
            <input type="submit" name="direction" class="direction-button" value="'.inputManager::LEFT.'">
            <input type="hidden" name="sizeInput" value="'.(isset($_POST['sizeInput']) ? $_POST['sizeInput'] : 4).'">
            <input type="hidden" name="previousGrid" value="'.htmlspecialchars(serialize($grid)).'">
        </form>';
    }
}