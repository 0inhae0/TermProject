<?php
class position {
    public $x;
    public $y;

    public function __construct(int $x = 0, int $y = 0) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getX() {
        return $this->x;
    }

    /**
     * @return mixed
     */
    public function getY() {
        return $this->y;
    }

    /**
     * @param mixed $x
     */
    public function setX($x) {
        $this->x = $x;
    }

    /**
     * @param mixed $y
     */
    public function setY($y) {
        $this->y = $y;
    }

    public function setPosition($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param position $position
     * @return bool
     */
    public function isEqual($position) {
        return (($position->getX() == $this->getX()) && ($position->getY() == $this->getY()));
    }
}