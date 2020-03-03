<?php


/**
 * Abstract strategy class, uses the strategy design pattern. To use this class, create a strategy class that extends
 * this class and implement the suggestMove() method in the subclass.
 */
abstract class Strategy {
    /**
     * Suggest a move for the computer to make.
     * @param Game $game The game on which to determine a move for.
     */
    abstract public function suggestMove(Game $game);
}