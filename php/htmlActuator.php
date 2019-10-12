<?php
class htmlActuator {
    public $tileContainer;
    public $dom;
    public $size;

    public function __construct() {
        $this->dom = new DOMDocument();
        $this->dom->loadHTMLFILE("index.php");
        $this->dom->preserveWhiteSpace = false;

        $this->tileContainer = $this->dom->getElementById("tile-container");
        foreach ($this->tileContainer->childNodes as $childNode) {
            $this->tileContainer->removeChild($childNode);
        }

        $this->score = 0;
    }

    public function actuate(grid $grid, $metadata) {
        $this->size = $grid->size;
        foreach ($grid->tiles as $tile) {
            $this->addTile($tile);
        }
    }

    public function addTile(tile $tile) {
        $wrapper = $this->dom->createElement("div");
        $tileInner = $this->dom->createElement("div");
        $position = isset($tile->previousPosition) ? $tile->previousPosition : ["x" => $tile->x, "y" => $tile->y];
        $positionClass = $this->positionClass($position);

        $classes = [htmlspecialchars("tile"), htmlspecialchars("tile-".$tile->value), htmlspecialchars($positionClass)];

        if ($tile->value > 20) {
            array_push($classes, htmlspecialchars("tile-super"));
        }

        $this->applyClasses($wrapper, $classes);

        $tileInner->setAttribute("class", htmlspecialchars("tile-inner"));

        {
            $tileInner->textContent = $tile->value;
            $gridSize = $this->size;
            $gridCellWidth = (500 - (20 * ($gridSize + 1))) / $gridSize;
        }
        $tileInner->setAttribute("style", htmlspecialchars("width: ".$gridCellWidth."px; height: ".$gridCellWidth."px;"));
        $this->dom->importNode($wrapper);
        $this->dom->importNode($this->tileContainer);
        $this->dom->importNode($tileInner);
        $wrapper->appendChild($tileInner);
        $this->tileContainer->appendChild($wrapper);
        $this->dom->importNode($this->tileContainer);
        $this->dom->saveHTMLFile("php/tileViewer.php");
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