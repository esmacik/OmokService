<?php

/**
 * Game class to represent and manipulate an Omok game at runtime.
 */
class Game {

    var $strategy;
    var $board;
    var $movesTaken = 0;

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
        $this->movesTaken += 1;
        $gameWonRow = $this->checkGameWon($x, $y, 1);
        if ($gameWonRow)
            return new Move($x, $y, true, false, $gameWonRow);
        return new Move($x, $y, false, false, []);
    }

    /**
     * Makes opponent move per the provided strategy.
     * @param $strategy Strategy Strategy object that will be used to make move.
     * @param $playerMoveX int Previous player move X coordinate.
     * @param $playerMoveY int Previous player move Y coordinate.
     * @return Move Return a move object acknowledging the move made.
     */
    function makeOpponentMove($strategy, $playerMoveX, $playerMoveY) {
        $opponentMove = $strategy->makeStrategyMove($this, $playerMoveX, $playerMoveY);
        $this->movesTaken += 1;
        $this->board[$opponentMove[0]][$opponentMove[1]] = 2;
        $gameWonRow = $this->checkGameWon($opponentMove[0], $opponentMove[1], 2);
        if ($gameWonRow)
            return new Move($opponentMove[0], $opponentMove[1], true, $this->checkDraw(), $gameWonRow);
        return new Move($opponentMove[0], $opponentMove[1], false, $this->checkDraw(), []);
    }

    /**
     * Check this game's board to determine if a draw occured has been made.
     * @return bool True if draw, false otherwise.
     */
    private function checkDraw() {
        return $this->movesTaken == SIZE * SIZE;
    }

    /**
     * If the given move is a winning one, return the coordinates of the win. Otherwise, return null;
     * @param $x int X coordinate of last move.
     * @param $y int Y coordiante of last move.
     * @param $player int 1 for human, 2 for computer
     * @return array|null Array of coordinates if the game has been won, null otherwise.
     */
    function checkGameWon($x, $y, $player) {
        if (($result1 = $this->gameWonHelper($x, $y - 1, $player, "n"))[0] + ($result2 = $this->gameWonHelper($x, $y + 1, $player, "s"))[0] >= 4) {
            $finalResult = $this->getCoordinates($result1, $result2);
            array_push($finalResult, $x, $y);
            return $finalResult;
        } else if (($result1 = $this->gameWonHelper($x + 1, $y, $player, "e"))[0] + ($result2 = $this->gameWonHelper($x - 1, $y, $player, "w"))[0] >= 4) {
            $finalResult = $this->getCoordinates($result1, $result2);
            array_push($finalResult, $x, $y);
            return $finalResult;
        } else if (($result1 = $this->gameWonHelper($x + 1, $y - 1, $player, "ne"))[0] + ($result2 = $this->gameWonHelper($x - 1, $y + 1, $player, "sw"))[0] >= 4) {
            $finalResult = $this->getCoordinates($result1, $result2);
            array_push($finalResult, $x, $y);
            return $finalResult;
        } else if (($result1 = $this->gameWonHelper($x - 1, $y - 1, $player, "nw"))[0] + ($result2 = $this->gameWonHelper($x + 1, $y + 1, $player, "se"))[0] >= 4) {
            $finalResult = $this->getCoordinates($result1, $result2);
            array_push($finalResult, $x, $y);
            return $finalResult;
        }
        return null;
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
        if ($x < 0 || $y < 0 || $x >= SIZE || $y >= SIZE || $this->board[$x][$y] == 0 || $this->board[$x][$y] != $player)
            return array(0);
        $searchResult = array();
        switch ($direction) {
            case "n":
                $searchResult = $this->gameWonHelper($x, $y - 1, $player, $direction);
                break;
            case "ne":
                $searchResult = $this->gameWonHelper($x + 1, $y - 1, $player, $direction);
                break;
            case "e":
                $searchResult = $this->gameWonHelper($x + 1, $y, $player, $direction);
                break;
            case "se":
                $searchResult = $this->gameWonHelper($x + 1, $y + 1, $player, $direction);
                break;
            case "s":
                $searchResult = $this->gameWonHelper($x, $y + 1, $player, $direction);
                break;
            case "sw":
                $searchResult = $this->gameWonHelper($x - 1, $y + 1, $player, $direction);
                break;
            case "w":
                $searchResult = $this->gameWonHelper($x - 1, $y, $player, $direction);
                break;
            case "nw":
                $searchResult = $this->gameWonHelper($x - 1, $y - 1, $player, $direction);
                break;
            default:
                break;
        }

        $finalResult = array(1, $x, $y);
        if ($searchResult) {
            $finalResult[0] += $searchResult[0];
            array_shift($searchResult);
            foreach ($searchResult as $value) {
                array_push($finalResult, $value);
            }
        }
        return $finalResult;
    }

    /**
     * Helper function that returns only coordinates of $this->gameWonHelper().
     * @param $result1 array Array of coordinates with number searched at first position.
     * @param $result2 array Array of coordinates with number searched at first position.
     * @return array Array of formatted coordinates.
     */
    private function getCoordinates($result1, $result2) {
        array_shift($result1);
        array_shift($result2);
        $finalResult= array();
        foreach ($result1 as $value)
            array_push($finalResult, $value);
        foreach ($result2 as $value)
            array_push($finalResult, $value);
        return $finalResult;
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
        foreach ($gameJsonObject->board as $row) {
            array_push($loadedGame->board, $row);
        }
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
