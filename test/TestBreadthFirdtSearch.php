<?php
require_once(dirname(__FILE__) . '/../lib/lime.php');
require_once(dirname(__FILE__) . '/../route.php');

$t = new lime_test();
$maze = dirname(__FILE__) . '/../resource/maze.txt';
$bad_maze = dirname(__FILE__) . '/../resource/bad_maze.txt';
$bad_maze2 = dirname(__FILE__) . '/../resource/bad_maze2.txt';
$not_found = dirname(__FILE__) . '/../resource/not_found.txt';

testBadFormatMaze($t, $bad_maze);
testBadFormatMaze($t, $bad_maze2);
testNotFoundMaze($t, $not_found);
testOut($t, $maze);

function testBadFormatMaze(lime_test &$t, $maze) {
    echo __FUNCTION__ . ' start >>' . PHP_EOL;
    try {
        new BreadthFirstSearch($maze);
        $t->fail();
    }
    catch (InvalidFormatException $e) {
        $t->pass($e->getMessage());
    }
    catch (Exception $e) {
        $t->fail($e->getMessage());
    }
    echo __FUNCTION__ . ' end <<' . PHP_EOL;
}

function testNotFoundMaze(lime_test &$t, $maze) {
    echo __FUNCTION__ . ' start >>' . PHP_EOL;
    try {
        new BreadthFirstSearch($maze);
        $t->fail();
    }
    catch (InvalidFormatException $e) {
        $t->fail($e->getMessage());
    }
    catch (Exception $e) {
        $t->pass($e->getMessage());
    }
    echo __FUNCTION__ . ' end <<' . PHP_EOL;
}

function testOut(lime_test &$t, $maze) {
    echo __FUNCTION__ . ' start >>' . PHP_EOL;
    try {
        $bfs = new BreadthFirstSearch($maze);
        $bfs->out();
        $t->pass();    
    }
    catch (InvalidFormatException $e) {
        $t->fail($e->getMessage());
    }
    catch (Exception $e) {
        $t->fail($e->getMessage());
    }
    echo __FUNCTION__ . ' end <<' . PHP_EOL;
}
?>