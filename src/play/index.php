<?php
// Requires a pid and a move (x,y)
// Returns a response (t,f), ack_move, and move
require "../play/Game.php";
require "../info/constants.php";
require "Move.php";
require "Strategy.php";
require "RandomStrategy.php";
require "SmartStrategy.php";

// Get user-provided pid and move
$pid = $_GET[PID];
$move = explode(",", $_GET[MOVE]);

// Find input errors
if (!$pid) { // pid not provided
    echo json_encode(array("response"=>false, "reason"=>"PID not provided"));
    return;
}
if ($move[0] < 0 || $move[0] >= SIZE || $move[1] < 0 || $move[1] >= SIZE) {
    echo json_encode(array("response"=>false, "reason"=>"Invalid move"));
    return;
}

// File to be read and written to for this run
$filename = "../writable/".$pid.".txt";

// Read Game object from json file
$openedFileJson = file_get_contents($filename);

if (!$openedFileJson)
    echo json_encode(array("respone"=>false, "reason"=>"Game not found"));

$loadedGame = Game::fromJson($openedFileJson);

// Make player-provided move
$playerMove = $loadedGame->makePlayerMove($move[0], $move[1]);

// Make opponent move with the given strategy
$strategy = new $strategiesMap[$loadedGame->strategy]();
$opponentMove = $loadedGame->makeOpponentMove($strategy, $move[0], $move[1]);

// Write the new game state to the file
Game::writeGameToFile($loadedGame, $filename);

echo json_encode(array("response"=>true, "ack_move"=>$playerMove, "move"=>$opponentMove));
