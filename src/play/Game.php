<?php

/**
 * Game class to represent and manipulate an Omok game at runtime.
 */
class Game {

    var $strategy;
    var $board;
    var $prevPlayerMove = array(-1, -1);
    var $prevStrategyMove = array(-1, -1);
    var $movesTaken = 0;

    /**
     * Create a new empty game with the given size and strategy.
     * @param $size int Size of square board ($size * $size).
     * @param $strategy string Strategy that this game will support. Must be one of the keys defined in $strategiesMap
     * from constants.php.
     */
    function __construct($size, $strategy) {
        $this->strategy = $strategy;
        $this->board = array();
        for ($i = 0; $i < $size; $i++) {
            $row = array();
            for ($j = 0; $j < $size; $j++) {
                array_push($row, 0);
            }
            array_push($this->board, $row);
        }
    }

    /**
     * Makes move at specified coordinate.
     * @param $x int x coordinate
     * @param $y int y coordinate
     * @return Move
     */
    function makePlayerMove($x, $y) {
        $this->board[$x][$y] = 1;
        $this->prevPlayerMove = array($x, $y);
        $this->movesTaken += 1;

        $gameWonRow = $this->getCoordinatesInRow($x, $y, 1);
        if ($gameWonRow)
            return new Move($x, $y, true, $this->checkDraw(), $gameWonRow);
        return new Move($x, $y, false, $this->checkDraw(), []);
    }

    /**
     * Makes opponent move per the provided strategy.
     * @param $strategy Strategy Strategy object that will be used to make move.
     * @return Move Return a move object acknowledging the move made.
     */
    function makeOpponentMove($strategy) {
        $opponentMove = $strategy->suggestMove($this);
        $this->board[$opponentMove[0]][$opponentMove[1]] = 2;
        $this->prevStrategyMove = array($opponentMove[0], $opponentMove[1]);
        $this->movesTaken += 1;

        $gameWonRow = $this->getCoordinatesInRow($opponentMove[0], $opponentMove[1], 2);
        if ($gameWonRow)
            return new Move($opponentMove[0], $opponentMove[1], true, $this->checkDraw(), $gameWonRow);
        return new Move($opponentMove[0], $opponentMove[1], false, $this->checkDraw(), []);
    }

    /**
     * Check this game's board to determine if a draw occurred has been made.
     * @return bool True if draw, false otherwise.
     */
    private function checkDraw() {
        return $this->movesTaken == SIZE * SIZE;
    }

    /**
     * If the given move is a winning one, return the coordinates of the win. Otherwise, return null.
     * @param $x int X coordinate of last move.
     * @param $y int Y coordiante of last move.
     * @param $player int 1 for human, 2 for computer
     * @return array|null Array of coordinates if the game has been won, null otherwise.
     */
    function getCoordinatesInRow($x, $y, $player) {
        $coordinates = null;
        // Check north and south for a win
        if ((count($result1 = $this->gameWonHelper($x, $y - 1, $player, "n")) + count($result2 = $this->gameWonHelper($x, $y + 1, $player, "s"))) / 2 >= 4) {
            $coordinates = array_merge($result1, [$x, $y], $result2);
        // Check east and west for a win
        } elseif ((count($result1 = $this->gameWonHelper($x + 1, $y, $player, "e")) + count($result2 = $this->gameWonHelper($x - 1, $y, $player, "w"))) / 2  >= 4) {
            $coordinates = array_merge($result1, [$x, $y], $result2);
        // Check northeast and southwest for a win
        } elseif ((count($result1 = $this->gameWonHelper($x + 1, $y - 1, $player, "ne")) + count($result2 = $this->gameWonHelper($x - 1, $y + 1, $player, "sw"))) / 2 >= 4) {
            $coordinates = array_merge($result1, [$x, $y], $result2);
        // Check northwest and southeast for a win
        } elseif ((count($result1 = $this->gameWonHelper($x - 1, $y - 1, $player, "nw")) + count($result2 = $this->gameWonHelper($x + 1, $y + 1, $player, "se"))) / 2 >= 4) {
            $coordinates = array_merge($result1, [$x, $y], $result2);
        }
        return $coordinates;
    }

    /**
     * Recursive helper function that returns an array containing the number of elements searched in a line in a
     * direction in the first position, and the coordinates searched.
     * @param $x int X coordinate to search
     * @param $y int Y coordinate to search
     * @param $player int 1 for human, 2 for computer
     * @param $direction string Search direction. Can be "n", "ne", "e", "se", "s", "sw", "w", or "nw".
     * @return array Number of elements searched in the first position, and coordinates.
     */
    private function gameWonHelper($x, $y, $player, $direction) {
        $searchResult = [];
        if ($x < 0 || $y < 0 || $x >= SIZE || $y >= SIZE || $this->board[$x][$y] != $player)
            return array();
        switch ($direction) {
            case "n": $searchResult = $this->gameWonHelper($x, $y - 1, $player, $direction);
                break;
            case "ne": $searchResult = $this->gameWonHelper($x + 1, $y - 1, $player, $direction);
                break;
            case "e": $searchResult = $this->gameWonHelper($x + 1, $y, $player, $direction);
                break;
            case "se": $searchResult = $this->gameWonHelper($x + 1, $y + 1, $player, $direction);
                break;
            case "s": $searchResult = $this->gameWonHelper($x, $y + 1, $player, $direction);
                break;
            case "sw": $searchResult = $this->gameWonHelper($x - 1, $y + 1, $player, $direction);
                break;
            case "w": $searchResult = $this->gameWonHelper($x - 1, $y, $player, $direction);
                break;
            case "nw": $searchResult = $this->gameWonHelper($x - 1, $y - 1, $player, $direction);
                break;
            default:
                break;
        }
        return array_merge([$x, $y], $searchResult);
    }

    /**
     * Creates a Game object represented by JSON string.
     * @param $gameJsonString string JSON string that represents a Game object.
     * @return Game Game object created from JSON string.
     */
    public static function fromJson($gameJsonString) {
        $gameJsonObject = json_decode($gameJsonString);
        $loadedGame = new Game(0, null);

        $loadedGame->strategy = $gameJsonObject->strategy;
        $loadedGame->movesTaken = $gameJsonObject->movesTaken;
        $loadedGame->prevPlayerMove = $gameJsonObject->prevPlayerMove;
        $loadedGame->prevStrategyMove = $gameJsonObject->prevStrategyMove;
        $loadedGame->board = $gameJsonObject->board;
        return $loadedGame;
    }

    /**
     * Creates a JSON string representation of this Game object.
     * @param $gameObject Game Game to convert to JSON String.
     * @return string JSON string that represents given Game object.
     */
    public static function toJson($gameObject) {
        return json_encode($gameObject);
    }

    /**
     * Writes the JSON representation of the given Game object to the given file.
     * @param $gameObject Game Game object to be written to file.
     * @param $filename string File to write Game object to.
     */
    public static function writeGameToFile($gameObject, $filename) {
        file_put_contents($filename, Game::toJson($gameObject));
    }
}
