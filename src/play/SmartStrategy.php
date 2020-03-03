<?php


/**
 * A smart strategy for the computer to use.
 */
class SmartStrategy extends Strategy {

    /**
     * Suggest a smart move for the given game.
     * @param Game $game The game on which to determine a move for.
     * @return array Array containing suggested x and y coordinates.
     */
    public function suggestMove(Game $game) {
        // Create array fo adjacent points
        $c = $game->prevPlayerMove;
        $pointsToTry = [
            [$c[0]-1, $c[1]-1],[$c[0], $c[1]-1],[$c[0]+1, $c[1]-1],
            [$c[0]-1, $c[1]],                   [$c[0]+1, $c[1]],
            [$c[0]-1, $c[1]+1],[$c[0], $c[1]+1],[$c[0]+1, $c[1]+1]
        ];

        // Look at the previous move taken by player. If there are two in a row, make a block.
        for ($i = 0; $i < count($pointsToTry); $i += 1) {
            $attempt = $pointsToTry[$i];
            $attemptOpposite = $pointsToTry[count($pointsToTry) - 1 - $i];
            if ($attemptOpposite[0] < 0 || $attemptOpposite[1] < 0 || $attemptOpposite[0] >= SIZE || $attemptOpposite[1] >= SIZE)
                continue;

            if ($game->board[$attempt[0]][$attempt[1]] == 1) {
                if ($game->board[$attemptOpposite[0]][$attemptOpposite[1]] == 0) {
                    return [$attemptOpposite[0], $attemptOpposite[1]];
                }
            }
        }

        // If player has no adjacent pieces to the one they just places, choose an adjacent spot
        foreach ($pointsToTry as $attempt) {
            if ($attempt[0] < 0 || $attempt[1] < 0 || $attempt[0] >= SIZE || $attempt[1] >= SIZE)
                continue;
            if ($game->board[$attempt[0]][$attempt[1]] == 0) {
                return [$attempt[0], $attempt[1]];
            }
        }

        // Worst case, just put make a random move.
        return (new RandomStrategy())->$this->suggestMove($this);
    }
}
