<?php

/**
 * Class that represents a move taken by a player or computer. Contains the x and y coordinates, whether or not the move
 * was a win, whether or not the move was a draw, and the row only if the move was a win.
 */
class Move {

    public $x;
    public $y;
    public $isWin;
    public $isDraw;
    public $row;

    /**
     * Construct a move with the given values. If the move was not a win, make $row = [].
     */
    function __construct($x, $y, $isWin, $isDraw, $row) {
        $this->x = $x;
        $this->y = $y;
        $this->isWin = $isWin;
        $this->isDraw = $isDraw;
        $this->row = $row;
    }
}