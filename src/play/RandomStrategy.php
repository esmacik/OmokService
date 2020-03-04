<?php


/**
 * A random strategy for the computer to use.
 */
class RandomStrategy implements Strategy {

    /**
     * Suggest a random move for the given game.
     * @param Game $game  The game on which to determine a move for.
     * @return array Array containing suggested x and y coordinates.
     */
    public function suggestMove(Game $game) {
        while ($game->board[$randX = rand(0, SIZE - 1)][$randY = rand(0, SIZE - 1)] != 0)
            continue;
        return array($randX, $randY);
    }
}
