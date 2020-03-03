<?php
// Requires a pid and a move (x,y)
// Returns a response (t,f), ack_move, and move

require "../info/constants.php";
require "Game.php";
require "Move.php";
require "Strategy.php";
require "RandomStrategy.php";
require "SmartStrategy.php";

// Get user-provided pid and move
$pid = $_GET[PID];
$move = explode(",", $_GET[MOVE]);

// File to be read and written to for this run
$filename = "../writable/".$pid.".txt";

// Find input errors
if (!$_GET[MOVE]) { // No move was provided
    echo json_encode(array(
        "response"=>false,
        "reason"=>"Move not provided"));
    exit();
}
if (count($move) != 2) { // Move not well formed
    echo json_encode(array(
        "response"=>false,
        "reason"=>"Move not well formed"
    ));
    exit();
}
if (!$pid) { // pid not provided
    echo json_encode(array(
        "response"=>false,
        "reason"=>"pid not provided"));
    exit();
}
if (!file_exists($filename)) { // Game file does not exist
    echo json_encode(array(
        "response"=>false,
        "reason"=>"Game not found"
    ));
    exit();
}
if ($move[0] < 0 || $move[0] >= SIZE || $move[1] < 0 || $move[1] >= SIZE) { // Move out of game board bounds
    echo json_encode(array(
        "response"=>false,
        "reason"=>"Invalid move"));
    exit();
}

// Read Game object from json file
$openedFileJson = file_get_contents($filename);

// Load game from json file
$loadedGame = Game::fromJson($openedFileJson);

// Check if move was already made
if ($loadedGame->board[$move[0]][$move[1]] != 0) {
    echo json_encode(array(
        "response"=>false,
        "reason"=>"Move already made"
    ));
    exit();
}

// Make player-provided move
$playerMove = $loadedGame->makePlayerMove($move[0], $move[1]);

// Make opponent move with the given strategy
$strategy = new $strategiesMap[$loadedGame->strategy]();
$opponentMove = $loadedGame->makeOpponentMove($strategy);

// Write the new game state to the file
Game::writeGameToFile($loadedGame, $filename);

// Send back response, acknowledgement of move, and computer made move
echo json_encode(array(
    "response"=>true,
    "ack_move"=>$playerMove,
    "move"=>$opponentMove));
