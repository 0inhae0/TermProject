<?php
require_once "simpleRequire.php";

/**
 * Class gridViewer
 * grid를 초기화하고 htmlTransmitter와 연결하는 클래스
 */
class gridViewer {
    /**
     * @var int 보드의 크기
     */
    public $gridSize;
    /**
     * @var grid 초기화 및 전달할 grid
     */
    public $grid;
    /**
     * @var htmlTransmitter 전달할 htmlTransmitter 객체. 객체인 것은 큰 상관이 없음.
     */
    public $htmlTransmitter;

    /**
     * gridViewer constructor.
     * htmlTransmitter만 초기화
     */
    public function __construct() {
        $this->htmlTransmitter = new htmlTransmitter();
    }

    /**
     *
     */
    public function init() {
        if (isset($_POST["sizeInput"])) {
            if (isset($_POST["previousGrid"])) {
                if (isset($_POST["direction"])) {
                    $this->setWithDirection($_POST["previousGrid"], $_POST["direction"]);
                    return;
                }
                for ($x = 0; $x < $_POST["sizeInput"]; $x++) {
                    for ($y = 0; $y < $_POST["sizeInput"]; $y++) {
                        if (isset($_POST[$x . "-" . $y])) {
                            $this->setWithGrid($_POST["previousGrid"], new position($x, $y));
                            return;
                        }
                    }
                }
            }
            $this->set($_POST["sizeInput"]);
            return;
        } else {
            $this->set(4);
            return;
        }
    }

    /**
     * @param int $size 만들 grid의 size
     */
    public function set(int $size) {
        $this->grid = new grid($size);
        $this->gridSize = $this->grid->size;
        $this->transmit();
    }

    /**
     * @param string $serializedPreviousGrid serialize 함수로 변환된 grid
     * @param position $positionClicked 클릭한 tile의 position을 나타내는 배열. ["x" => $x, "y" => $y]의 형식
     */
    public function setWithGrid(string $serializedPreviousGrid, position $positionClicked) {
        $this->grid = unserialize(htmlspecialchars_decode($serializedPreviousGrid));
        $this->gridSize = $this->grid->size;
        $this->grid->moveTile($positionClicked);
        $this->transmit();
    }

    public function setWithDirection(string $serializedPreviousGrid, string $DIRECTION) {
        $this->grid = unserialize(htmlspecialchars_decode($serializedPreviousGrid));
        $this->gridSize = $this->grid->size;
        if($DIRECTION=="AUTO")
            $this->grid->moveTileAuto();
        $this->grid->moveTileWithDirection($DIRECTION);
        $this->transmit();
    }

    /**
     * htmlTransmitter를 이용해 index.php에 전송
     */
    public function transmit() {
        $this->htmlTransmitter->transmitTileContainer($this);
    }
}
