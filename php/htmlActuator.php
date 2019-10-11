<?php
class htmlActuator {
    public $tileContainer;
    public $dom;
    public $size;

    public function __construct() {
        $this->dom = new DOMDocument();
        $this->dom->loadHTMLFile("index.php");
        $this->dom->preserveWhiteSpace = true;

        $this->tileContainer = $this->dom->getElementById("tile-container");

        $this->score = 0;
    }

    public function actuate(grid $grid, $metadata) {
        $this->size = $grid->size;
        foreach ($grid->cells as $tile) {
            $this->addTile($tile);
        }
    }

    public function addTile(tile $tile) {
        $wrapper = $this->dom->createElement("div");
        $this->dom->appendChild($wrapper);
        $tileInner = $this->dom->createElement("div");
        $this->dom->appendChild($tileInner);
        $position = isset($tile->previousPosition) ? $tile->previousPosition : ["x" => $tile->x, "y" => $tile->y];
        $positionClass = $this->positionClass($position);

        $classes = ["tile", "tile-".$tile->value, $positionClass];

        if ($tile->value > 20) {
            array_push($classes, "tile-super");
        }

        $this->applyClasses($wrapper, $classes);

        $tileInner = $this->dom->createElement("tile-inner");

        {
            $tileInner->textContent = $tile->value;
            $gridSize = $this->size;
            $gridCellWidth = (500 - (20 * ($gridSize + 1))) / $gridSize;
            $xPosition = ($tile->x + 1) * 20 + $tile->x * $gridCellWidth;
            $yPosition = ($tile->y + 1) * 20 + $tile->y * $gridCellWidth;
        }
        $tileInner->setAttribute("style", "width: ".$gridCellWidth."px; height: ".$gridCellWidth."px; transform: translate(".$xPosition."px, ".$yPosition."px);");
        $wrapper->appendChild($tileInner);
        $this->tileContainer->appendChild($wrapper);
        $this->dom->saveHTMLFile("index.php");
    }

    public function positionClass($position) {
        $position = $this->normalizePosition($position);
        return "tile-position-".$position["x"]."-".$position["y"];
    }

    public function normalizePosition($position) {
        return ["x" => $position["x"] + 1, "y" => $position["y"] + 1];
    }

    public function applyClasses(DOMElement &$element, $classes) {
        $element->setAttribute("class", join(" ", $classes));
    }
}