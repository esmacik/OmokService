<?php


/**
 * A Strategy interface, uses the strategy design pattern. To use this class, create a strategy class that extends
 * this class and implement the suggestMove() method in the subclass.
 */
interface Strategy {
    /**
     * Suggest a move for the computer to make.
     * @param Game $game The game on which to determine a move for.
     */
    public function suggestMove(Game $game);
}