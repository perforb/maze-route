<?php

set_error_handler(function ($severity, $message, $filename, $lineno) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
});

try {
    $maze = isset($argv[1]) ? $argv[1] : dirname(__FILE__) . '/resource/maze.txt';
    $bfs = new BreadthFirstSearch($maze);
    $bfs->out();
}
catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    echo 'Stac Trace: ' . $e->getTraceAsString(), PHP_EOL;
    error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString());
}

class BreadthFirstSearch {

    const START   = 'S';
    const GOAL    = 'G';
    const ROUTE   = '$';
    const MARKING = '#';

    private $maze;
    private $width;
    private $height;

    private $directions = array(
        array(0, -1),
        array(1, 0),
        array(0, 1),
        array(-1, 0),
    );

    public function __construct($maze) {
        if (!isset($maze) || !is_file($maze) || !is_readable($maze)) {
            throw new Exception(sprintf("Can't read %s.\n", $maze));
        }
        $this->maze = file($maze);
        if (!$this->areSandGInclude($this->maze)) throw new InvalidFormatException("Invalid file format.\n");
        $this->setSize($this->maze);
    }

    private function areSandGInclude(array &$maze) {
        $s = false;
        $g = false;
        $row_count = count($maze);
        for ($i = 0; $i < $row_count; $i++) {
            $columun_count = mb_strlen($maze[$i]);
            for ($j = 0; $j < $columun_count; $j++) {
                if ($maze[$i][$j] === self::START) $s = true;
                if ($maze[$i][$j] === self::GOAL) $g = true;
            }
        }
        return ($s && $g);
    }

    private function setSize(array &$maze) {
        $this->height = count($maze);
        for ($i = 0; $i < $this->height; $i++) {
            if ($i === 0) {
                $this->width = rtrim(mb_strlen($maze[$i]));
                continue;
            }
            if ($this->width !== rtrim(mb_strlen($maze[$i]))) {
                throw new InvalidFormatException("Invalid file format.\n");
            }
        }
    }

    public function out() {
        $sx = $sy = $gx = $gy = 0;
        $map = &$this->maze;
        $route_map = null;

        for ($i = 0; $i < $this->height; $i++) {
            $route_map[$i] = array();
            for ($j = 0; $j < $this->width; $j++) {
                $route_map[$i][$j] = 0;
                if (self::START === $map[$i][$j]) {
                    $sx = $j;
                    $sy = $i;
                }
                elseif (self::GOAL === $map[$i][$j]) {
                    $gx = $j;
                    $gy = $i;
                }
            }
        }

        $route = 1;
        $queue[] = array($sx, $sy);
        $org_map = $map;

        while (0 < count($queue)) {
            list($x, $y) = array_shift($queue);
            foreach ($this->directions as $direction) {
                $dx = $x + $direction[0];
                $dy = $y + $direction[1];
                if (self::GOAL === $map[$dy][$dx]) {
                    $route_map[$dy][$dx] = $route;
                    break 2;
                }
                elseif (' ' === $map[$dy][$dx]) {
                    $map[$dy][$dx] = self::MARKING;
                    $route_map[$dy][$dx] = $route;
                    array_push($queue, array($dx, $dy));
                }
            }
            $route++;
        }

        $x = $gx;
        $y = $gy;
        $route = $route_map[$gy][$gx];

        while (1 < $route) {
            $route--;
            foreach ($this->directions as $direction) {
                $dx = $x + $direction[0];
                $dy = $y + $direction[1];
                if ($route_map[$dy][$dx] === $route) {
                    $org_map[$dy][$dx] = self::ROUTE;
                    $x = $dx;
                    $y = $dy;
                }
            }
        }

        echo implode($org_map), PHP_EOL;
    }
}

class InvalidFormatException extends Exception {

    public function __construct($message) {
        parent::__construct($message);
    }
}
?>
