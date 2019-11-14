<?php
class position implements Serializable {
    private $x;
    private $y;

    public function __construct($x = 0, $y = 0) {
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
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() {
        // TODO: Implement serialize() method.
        return 'O:8:"position":2:{s:1:"x";i:'.$this->getX().';s:1:"y";i:'.$this->getY().';}';
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized) {
        // TODO: Implement unserialize() method.
        if (preg_match_all('/O:8:"position":2:{s:1:\"x\";i:([0-9]+);s:1:\"y\";i:([0-9]+);}/', $serialized, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $x = (int)$match[1];
                $y = (int)$match[2];
            }
        }
        if (isset($x) && isset($y)) {
            $this->setX($x);
            $this->setY($y);
        }
    }
}