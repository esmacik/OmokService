<?php //index.php
// Requires a strategy parameter
// Return a response, pid, and reason if error

require "../info/constants.php";
require "../play/Game.php";

// Get user-provided strategy
$enteredStrategy = $_GET[STRATEGY];

// Check for input errors
if (!$enteredStrategy) { // No strategy provided
    echo json_encode(array(
        "response"=>false,
        "reason"=>"No strategy provided."));
    return;
}
if(!in_array($enteredStrategy, STRATEGIES)) { // Strategy not supported
    echo json_encode(array(
        "response"=>false,
        "reason"=>"Strategy not supported."));
    return;
}

// Encode unique game id
$pid = uniqid();
$filename = "../writable/".$pid.".txt";

// Create new game object
$blankGame = new Game(SIZE, $enteredStrategy);

// Write new game with strategy to file at filename
Game::writeGameToFile($blankGame, $filename);

// Respond with successful resonse
echo json_encode(array(
    "response"=>true,
    "pid"=>$pid));
